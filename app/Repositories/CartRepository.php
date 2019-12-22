<?php

namespace App\Repositories;

use App\Models\{Cart, OrderItem, ProductReference, User};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CartRepository
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    public function __construct (ProductRepository $productRepository, ModuleRepository $moduleRepository)
    {
        $this->productRepository = $productRepository;
        $this->moduleRepository = $moduleRepository;
    }

    public function getCart (User $user): Cart
    {
        return Cart::query()
            ->firstOrCreate([
                'user_id' => $user->id
            ], [
                'items_count'                  => 0,
                'total_amount_excluding_taxes' => 0,
                'total_amount_including_taxes' => 0,
                'address_id'                   => null
            ]);
    }

    public function getItems (Cart $cart): array
    {
        return $cart
            ->items()
            ->select(['quantity', 'product_reference_id'])
            ->get()
            ->keyBy('product_reference_id')
            ->toArray();
    }

    public function deleteItem (ProductReference $productReference, Cart $cart): void
    {
        $cartItem = $cart
            ->items()
            ->where([
                'product_reference_id' => $productReference->id,
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

    public function mergeItemsOnDatabase (array $cookieItems, array $databaseItems, Cart $cart): void
    {
        if (empty($cookieItems)) {
            return;
        }

        $updates = [];
        $additions = [];
        foreach ($cookieItems as $productReferenceId => $cookieItem) {
            $databaseItem = $databaseItems[$productReferenceId] ?? null;

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

    public function updateItem (int $quantity, ProductReference $productReference, Cart $cart): void
    {

        $cartItem = $cart
            ->items()
            ->where(['product_reference_id' => $productReference->id])
            ->firstOrFail();

        $cartItem->amount_excluding_taxes = $quantity * $productReference->unit_price_excluding_taxes;
        $cartItem->amount_including_taxes = $quantity * $productReference->unit_price_including_taxes;
        $cartItem->quantity = $quantity;

        $cart->total_amount_excluding_taxes += -$cartItem->getOriginal('amount_excluding_taxes') + $cartItem->amount_excluding_taxes;
        $cart->total_amount_including_taxes += -$cartItem->getOriginal('amount_including_taxes') + $cartItem->amount_including_taxes;

        $cartItem->save();
        $cart->save();
    }

    public function addItem (int $quantity, ProductReference $productReference, Cart $cart): void
    {
        $cartItem = $cart->items()->save(
            new OrderItem([
                'quantity'               => $quantity,
                'product_reference_id'   => $productReference->id,
                'amount_including_taxes' => $quantity * $productReference->unit_price_including_taxes,
                'amount_excluding_taxes' => $quantity * $productReference->unit_price_excluding_taxes,
            ])
        );

        $cart->total_amount_excluding_taxes += $cartItem->amount_excluding_taxes;
        $cart->total_amount_including_taxes += $cartItem->amount_including_taxes;
        $cart->items_count++;

        $cartItem->save();
        $cart->save();
    }
}
