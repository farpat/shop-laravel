<?php

namespace App\Repositories;

use App\Models\{Cart, CartItem, ProductReference, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartRepository
{
    /**
     * @var Collection
     */
    private $items;
    /**
     * @var Cart|null
     */
    private $cart;
    /**
     * @var User|null
     */
    private $currentUser;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct (ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->currentUser = auth()->user();
        $this->setItems();
    }

    public function returnArray (ProductReference $productReference, int $quantity = null)
    {
        $productReferenceArray = $productReference->toArray();
        unset($productReferenceArray['product']);
        $data = [
            'product_reference_id'   => $productReference->id,
            'amount_including_taxes' => $quantity * $productReference->unit_price_including_taxes,
            'amount_excluding_taxes' => $quantity * $productReference->unit_price_excluding_taxes,
            'product_reference'      => $productReferenceArray
        ];

        if ($quantity !== null) {
            $data['quantity'] = $quantity;
        }

        return $data;
    }

    public function addItem (int $quantity, ProductReference $productReference): array
    {
        $this->items->put($productReference->id, [
            'quantity'             => $quantity,
            'product_reference_id' => $productReference->id,
        ]);

        if ($this->currentUser !== null) {
            $this->addItemOnDatabase($quantity, $productReference);
        } else {
            $this->persistCookie();
        }

        return $this->returnArray($productReference, $quantity);
    }

    public function updateItem (int $quantity, ProductReference $productReference): array
    {
        $this->items->put($productReference->id, [
            'quantity'             => $quantity,
            'product_reference_id' => $productReference->id,
        ]);

        if ($this->currentUser !== null) {
            $this->updateItemOnDatabase($quantity, $productReference);
        } else {
            $this->persistCookie();
        }

        return $this->returnArray($productReference, $quantity);
    }

    public function deleteItem (ProductReference $productReference): array
    {
        $this->items->forget($productReference->id);

        if ($this->currentUser !== null) {
            $this->deleteItemOnDatabase($productReference);
        } else {
            $this->persistCookie();
        }

        return $this->returnArray($productReference);
    }

    public function getCart (User $user, bool $dontCreate = true): ?Cart
    {
        if ($dontCreate) {
            return Cart::query()
                ->where([
                    'status'  => Cart::ORDERING_STATUS,
                    'user_id' => $user->id
                ])
                ->first();
        }

        return Cart::query()
            ->firstOrCreate([
                'status'  => Cart::ORDERING_STATUS,
                'user_id' => $user->id
            ], [
                'items_count'                  => 0,
                'total_amount_excluding_taxes' => 0,
                'total_amount_including_taxes' => 0,
                'user_id'                      => $user->id,
                'address_id'                   => null
            ]);
    }

    private function setItems ()
    {
        if ($this->items === null) {
            if ($this->currentUser !== null) {
                $this->cart = $this->getCart($this->currentUser);

                $items = $this->cart ? CartItem::query()
                    ->select(['quantity', 'product_reference_id'])
                    ->where(['cart_id' => $this->cart->id])
                    ->get()
                    ->keyBy('product_reference_id')
                    ->all() : [];
            } else {
                $items = json_decode(Cookie::get('cart-items', '{}'), true);
            }

            $this->items = collect($items);
        }
    }

    public function getItems (): array
    {
        $items = $this->items->all();

        $productReferences = $this->productRepository->getReferences(!empty($items) ? array_keys($items) : null);

        foreach ($items as $productReferenceId => $item) {
            $quantity = $item['quantity'];
            $productReference = $productReferences->get($productReferenceId);

            $items[$productReferenceId]['product_reference'] = $productReference;
            $items[$productReferenceId]['amount_excluding_taxes'] = $quantity * $productReference->unit_price_excluding_taxes;
            $items[$productReferenceId]['amount_including_taxes'] = $quantity * $productReference->unit_price_including_taxes;
        }

        return $items;
    }

    private function persistCookie ()
    {
        Cookie::queue('cart-items', $this->items->toJson());
    }

    private function deleteItemOnDatabase (ProductReference $productReference)
    {
        $cart = $this->getCart($this->currentUser, false);

        $cartItem = CartItem::query()
            ->where([
                'product_reference_id' => $productReference->id,
                'cart_id'              => $cart->id
            ])
            ->firstOrFail();

        $cart->total_amount_excluding_taxes -= $cartItem->amount_excluding_taxes;
        $cart->total_amount_including_taxes -= $cartItem->amount_including_taxes;
        $cart->items_count--;

        $cartItem->delete();
        if ($cart->items_count === 0) {
            $cart->delete();
        } else {
            $cart->save();
        }
    }

    private function updateItemOnDatabase (int $quantity, ProductReference $productReference)
    {
        $cart = $this->getCart($this->currentUser, false);

        $cartItem = CartItem::query()
            ->where([
                'product_reference_id' => $productReference->id,
                'cart_id'              => $cart->id
            ])
            ->firstOrFail();

        $cartItem->amount_excluding_taxes = $quantity * $productReference->unit_price_excluding_taxes;
        $cartItem->amount_including_taxes = $quantity * $productReference->unit_price_including_taxes;
        $cartItem->quantity = $quantity;

        $cart->total_amount_excluding_taxes += - $cartItem->getOriginal('amount_excluding_taxes') + $cartItem->amount_excluding_taxes;
        $cart->total_amount_including_taxes += - $cartItem->getOriginal('amount_including_taxes') + $cartItem->amount_including_taxes;

        $cartItem->save();
        $cart->save();
    }

    private function addItemOnDatabase (int $quantity, ProductReference $productReference)
    {
        $cart = $this->getCart($this->currentUser, false);

        $cartItem = new CartItem([
            'quantity'               => $quantity,
            'product_reference_id'   => $productReference->id,
            'amount_including_taxes' => $quantity * $productReference->unit_price_including_taxes,
            'amount_excluding_taxes' => $quantity * $productReference->unit_price_excluding_taxes,
            'cart_id'                => $cart->id
        ]);

        $cart->total_amount_excluding_taxes += $cartItem->amount_excluding_taxes;
        $cart->total_amount_including_taxes += $cartItem->amount_including_taxes;
        $cart->items_count++;

        $cartItem->save();
        $cart->save();
    }
}
