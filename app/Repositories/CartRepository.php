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
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct (ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
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

    public function getItems(Cart $cart): array
    {
        return CartItem::query()
            ->select(['quantity', 'product_reference_id'])
            ->where(['cart_id' => $cart->id])
            ->get()
            ->keyBy('product_reference_id')
            ->toArray();
    }

    public function addItem (int $quantity, ProductReference $productReference, Cart $cart): void
    {
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

    public function updateItem (int $quantity, ProductReference $productReference, Cart $cart): void
    {
        $cartItem = CartItem::query()
            ->where([
                'product_reference_id' => $productReference->id,
                'cart_id'              => $cart->id
            ])
            ->firstOrFail();

        $cartItem->amount_excluding_taxes = $quantity * $productReference->unit_price_excluding_taxes;
        $cartItem->amount_including_taxes = $quantity * $productReference->unit_price_including_taxes;
        $cartItem->quantity = $quantity;

        $cart->total_amount_excluding_taxes += -$cartItem->getOriginal('amount_excluding_taxes') + $cartItem->amount_excluding_taxes;
        $cart->total_amount_including_taxes += -$cartItem->getOriginal('amount_including_taxes') + $cartItem->amount_including_taxes;

        $cartItem->save();
        $cart->save();
    }

    public function deleteItem (ProductReference $productReference, Cart $cart): void
    {
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

    public function mergeItemsOnDatabase (Collection $cookieItems, Cart $cart): void
    {
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
            $this->updateItem($quantity, $productReferences->get($productReferenceId), $cart);
        }

        foreach ($additions as $productReferenceId => $quantity) {
            $this->addItem($quantity, $productReferences->get($productReferenceId), $cart);
        }
    }
}
