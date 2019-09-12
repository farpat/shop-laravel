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

    public function refreshItems (): void
    {
        if ($user = $this->auth->user()) {
            $this->refreshCart();

            $items = $this->cart->items_count > 0 ? CartItem::query()
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

    private function refreshCart (): void
    {
        $this->cart = $this->getCart($this->auth->user());
    }

    public function getCart (User $user): Cart
    {
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

    public function getCookieItems (): array
    {
        return json_decode(Cookie::get('cart-items', '[]'), true);
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

        return $this->returnArrayForResponse($productReference, $quantity);
    }

    private function addItemOnDatabase (int $quantity, ProductReference $productReference): void
    {
        $cartItem = new CartItem([
            'quantity'               => $quantity,
            'product_reference_id'   => $productReference->id,
            'amount_including_taxes' => $quantity * $productReference->unit_price_including_taxes,
            'amount_excluding_taxes' => $quantity * $productReference->unit_price_excluding_taxes,
            'cart_id'                => $this->cart->id
        ]);

        $this->cart->total_amount_excluding_taxes += $cartItem->amount_excluding_taxes;
        $this->cart->total_amount_including_taxes += $cartItem->amount_including_taxes;
        $this->cart->items_count++;

        $cartItem->save();
        $this->cart->save();
    }

    private function persistCookie (?Collection $items): void
    {
        $cookie = ($items === null) ?
            Cookie::forget('cart-items') :
            Cookie::make('cart-items', $items->toJson());

        Cookie::queue($cookie);
    }

    private function returnArrayForResponse (ProductReference $productReference, int $quantity = null): array
    {
        $productReferenceArray = $productReference->toArray();
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

        return $this->returnArrayForResponse($productReference, $quantity);
    }

    private function updateItemOnDatabase (int $quantity, ProductReference $productReference): void
    {
        $cartItem = CartItem::query()
            ->where([
                'product_reference_id' => $productReference->id,
                'cart_id'              => $this->cart->id
            ])
            ->firstOrFail();

        $cartItem->amount_excluding_taxes = $quantity * $productReference->unit_price_excluding_taxes;
        $cartItem->amount_including_taxes = $quantity * $productReference->unit_price_including_taxes;
        $cartItem->quantity = $quantity;

        $this->cart->total_amount_excluding_taxes += -$cartItem->getOriginal('amount_excluding_taxes') + $cartItem->amount_excluding_taxes;
        $this->cart->total_amount_including_taxes += -$cartItem->getOriginal('amount_including_taxes') + $cartItem->amount_including_taxes;

        $cartItem->save();
        $this->cart->save();
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

        return $this->returnArrayForResponse($productReference);
    }

    private function deleteItemOnDatabase (ProductReference $productReference): void
    {
        $cartItem = CartItem::query()
            ->where([
                'product_reference_id' => $productReference->id,
                'cart_id'              => $this->cart->id
            ])
            ->firstOrFail();

        $this->cart->total_amount_excluding_taxes -= $cartItem->amount_excluding_taxes;
        $this->cart->total_amount_including_taxes -= $cartItem->amount_including_taxes;
        $this->cart->items_count--;

        $cartItem->delete();
        if ($this->cart->items_count === 0) {
            $this->cart->delete();
        } else {
            $this->cart->save();
        }
    }

    public function getItems (): array
    {
        $items = $this->items->all();

        if (!empty($items)) {
            $productReferences = $this->productRepository->getReferences(array_keys($items));

            //obliged to re-compute the keys " product_reference and amounts " because the informations can change in the meantime
            foreach ($items as $productReferenceId => $item) {
                $productReference = $productReferences->get($productReferenceId);

                $items[$productReferenceId]['product_reference'] = $productReference;
                $items[$productReferenceId]['amount_excluding_taxes'] = $item['quantity'] * $productReference->unit_price_excluding_taxes;
                $items[$productReferenceId]['amount_including_taxes'] = $item['quantity'] * $productReference->unit_price_including_taxes;
            }
        }

        return $items;
    }

    public function mergeItemsOnDatabase (Collection $cookieItems): void
    {
        $this->persistCookie(null);

        if ($cookieItems->isEmpty()) {
            return;
        }

        $updates = [];
        $additions = [];
        foreach ($cookieItems as $productReferenceId => $cookieItem) {
            $databaseItem = $this->items->get($productReferenceId);

            if ($databaseItem !== null && $cookieItem['quantity'] > $databaseItem['quantity']) { //database item present with quantity lower
                $updates[$productReferenceId] = $cookieItem['quantity'];
            }

            if ($databaseItem === null) {  //database item not present
                $additions[$productReferenceId] = $cookieItem['quantity'];
            }
        }

        if (empty($updates) && empty($additions)) {
            return;
        }

        $productReferences = $this->productRepository->getReferences(array_keys($updates) + array_keys($additions));

        foreach ($updates as $productReferenceId => $quantity) {
            $this->updateItemOnDatabase($quantity, $productReferences->get($productReferenceId));
        }

        foreach ($additions as $productReferenceId => $quantity) {
            $this->addItemOnDatabase($quantity, $productReferences->get($productReferenceId));
        }
    }
}
