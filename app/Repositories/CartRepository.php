<?php

namespace App\Repositories;

use App\Models\{Cart, CartItem, ProductReference, User};
use Exception;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;
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
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var SessionGuard
     */
    private $auth;

    public function __construct (ProductRepository $productRepository, Guard $auth)
    {
        $this->productRepository = $productRepository;
        $this->auth = $auth;
        $this->refreshItems();
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
        if ($this->items->get($productReference->id) !== null) {
            throw new Exception(__('Product reference already present in cart'), 422);
        }

        $this->items->put($productReference->id, [
            'quantity'             => $quantity,
            'product_reference_id' => $productReference->id,
        ]);

        if ($this->auth->user() !== null) {
            $this->addItemOnDatabase($quantity, $productReference);
        } else {
            $this->persistCookie($this->items);
        }

        return $this->returnArray($productReference, $quantity);
    }

    public function updateItem (int $quantity, ProductReference $productReference): array
    {
        if ($this->items->get($productReference->id) === null) {
            throw new Exception(__('Product reference not present in cart'), 422);
        }

        $this->items->put($productReference->id, [
            'quantity'             => $quantity,
            'product_reference_id' => $productReference->id,
        ]);

        if ($this->auth->user() !== null) {
            $this->updateItemOnDatabase($quantity, $productReference);
        } else {
            $this->persistCookie($this->items);
        }

        return $this->returnArray($productReference, $quantity);
    }

    public function deleteItem (ProductReference $productReference): array
    {
        if ($this->items->get($productReference->id) === null) {
            throw new Exception(__('Product reference not present in cart'), 422);
        }

        $this->items->forget($productReference->id);

        if ($this->auth->user() !== null) {
            $this->deleteItemOnDatabase($productReference);
        } else {
            $this->persistCookie($this->items);
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

    public function refreshItems ()
    {
        if ($user = $this->auth->user()) {
            $this->cart = $this->getCart($user);

            $items = $this->cart ? CartItem::query()
                ->select(['quantity', 'product_reference_id'])
                ->where(['cart_id' => $this->cart->id])
                ->get()
                ->keyBy('product_reference_id')
                ->toArray() : [];
        } else {
            $items = $this->getCookieItems();
        }

        $this->items = collect($items);
    }

    public function getItems (): array
    {
        $items = $this->items->all();

        if (!empty($items)) {
            $productReferences = $this->productRepository->getReferences(array_keys($items));

            foreach ($items as $productReferenceId => $item) {
                $quantity = $item['quantity'];
                $productReference = $productReferences->get($productReferenceId);

                $items[$productReferenceId]['product_reference'] = $productReference;
                $items[$productReferenceId]['amount_excluding_taxes'] = $quantity * $productReference->unit_price_excluding_taxes;
                $items[$productReferenceId]['amount_including_taxes'] = $quantity * $productReference->unit_price_including_taxes;
            }
        }

        return $items;
    }

    private function persistCookie (?Collection $items)
    {
        $cookie = ($items === null) ?
            Cookie::forget('cart-items') :
            Cookie::make('cart-items', $items->toJson());

        Cookie::queue($cookie);
    }

    public function getCookieItems (): array
    {
        return json_decode(Cookie::get('cart-items', '[]'), true);
    }

    private function deleteItemOnDatabase (ProductReference $productReference)
    {
        $cart = $this->getCart($this->auth->user(), false);

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
        $cart = $this->getCart($this->auth->user(), false);

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
        $cart = $this->getCart($this->auth->user(), false);

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

    public function mergeItemsOnDatabase (Collection $cookieItems)
    {
        $this->persistCookie(null);

        if ($cookieItems->isEmpty()) {
            return;
        }

        $updates = [];
        $addings = [];
        foreach ($cookieItems as $productReferenceId => $cookieItem) {
            $databaseItem = $this->items->get($productReferenceId);

            if ($databaseItem !== null && $cookieItem['quantity'] > $databaseItem['quantity']) { //database item present with quantity lower
                $updates[$productReferenceId] = $cookieItem['quantity'];
            }

            if ($databaseItem === null) {  //database item not present
                $addings[$productReferenceId] = $cookieItem['quantity'];
            }
        }

        if (empty($updates) && empty($addings)) {
            return;
        }

        $productReferences = $this->productRepository->getReferences(array_keys($updates) + array_keys($addings));

        foreach ($updates as $productReferenceId => $quantity) {
            $this->updateItemOnDatabase($quantity, $productReferences->get($productReferenceId));
        }

        foreach ($addings as $productReferenceId => $quantity) {
            $this->addItemOnDatabase($quantity, $productReferences->get($productReferenceId));
        }
    }
}
