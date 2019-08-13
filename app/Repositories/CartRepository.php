<?php

namespace App\Repositories;

use App\Models\{Cart, CartItem, ProductReference, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
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

    /**
     * @param int $quantity
     * @param ProductReference $productReference
     *
     * @return array
     */
    public function addItem (int $quantity, ProductReference $productReference): array
    {
        $this->items->put($productReference->id, [
            'quantity'             => $quantity,
            'product_reference_id' => $productReference->id,
        ]);

        $cartItem = $this->persist('add', $productReference, $quantity);

        return $this->returnResponseData($cartItem, $productReference);
    }

    private function returnResponseData (CartItem $cartItem, ProductReference $productReference)
    {
        $productReference = $productReference->toArray();
        unset($productReference['product']);

        return array_merge($cartItem->toArray(), ['product_reference' => $productReference]);
    }

    public function updateItem (int $quantity, ProductReference $productReference): array
    {
        $this->items->put($productReference->id, [
            'quantity'             => $quantity,
            'product_reference_id' => $productReference->id,
        ]);

        $cartItem = $this->persist('update', $productReference, $quantity);

        return $this->returnResponseData($cartItem, $productReference);
    }

    public function deleteItem (ProductReference $productReference): array
    {
        $this->items->forget($productReference->id);

        $cartItem = $this->persist('delete', $productReference);

        return $cartItem->toArray();
    }

    public function getCart (User $user): Cart
    {
        return Cart::query()
            ->whereHas('user', function (Builder $query) use ($user) {
                $query->where('id', $user->id);
            })
            ->firstOrNew([
                'status', Cart::ORDERING_STATUS
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
            if ($this->currentUser) {
            } else {
                $items = json_decode(Cookie::get('cart-items', '{}'), true);
            }

            $this->items = collect($items);
        }
    }

    /**
     * @return Collection
     */
    public function getItems ()
    {
        $items = $this->items->all();

        $productReferences = $this->productRepository->getReferences(array_keys($items));

        foreach ($items as $productReferenceId => $item) {
            $quantity = $item['quantity'];
            $productReference = $productReferences->get($productReferenceId);

            $items[$productReferenceId]['product_reference'] = $productReference;
            $items[$productReferenceId]['amount_excluding_taxes'] = $quantity * $productReference->unit_price_excluding_taxes;
            $items[$productReferenceId]['amount_including_taxes'] = $quantity * $productReference->unit_price_including_taxes;
        }

        return collect($items);
    }

    private function persist (string $operation, ProductReference $productReference, int $quantity = null): ?CartItem
    {
        $cartItem = null;
        switch ($operation) {
            case 'add':
                /*
                if ($this->currentUser) {
                    $this->cart->total_amount_excluding_taxes += $cartItem->amount_excluding_taxes;
                    $this->cart->total_amount_including_taxes += $cartItem->amount_including_taxes;
                    $this->cart->items_count++;

                    $cartItem->save();
                    $this->cart->save();
                }
                */

                $cartItem = new CartItem([
                    'quantity'               => $quantity,
                    'product_reference_id'   => $productReference->id,
                    'amount_including_taxes' => $quantity * $productReference->unit_price_including_taxes,
                    'amount_excluding_taxes' => $quantity * $productReference->unit_price_excluding_taxes,
                ]);
                break;
            case 'update':
                /*
                if ($this->currentUser) {
                    $cartItem->amount_excluding_taxes = $quantity * $productReference->unit_price_excluding_taxes;
                    $cartItem->amount_including_taxes = $quantity * $productReference->unit_price_including_taxes;

                    $this->cart->total_amount_excluding_taxes -=
                        $cartItem->getOriginal('amount_excluding_taxes') + $cartItem->amount_excluding_taxes;
                    $this->cart->total_amount_including_taxes -=
                        $cartItem->getOriginal('amount_including_taxes') + $cartItem->amount_including_taxes;

                    $cartItem->save();
                    $this->cart->save();
                } */

                $cartItem = new CartItem([
                    'quantity'               => $quantity,
                    'product_reference_id'   => $productReference->id,
                    'amount_including_taxes' => $quantity * $productReference->unit_price_including_taxes,
                    'amount_excluding_taxes' => $quantity * $productReference->unit_price_excluding_taxes,
                ]);


                break;
            case 'delete':
                /*
                if ($this->currentUser) {
                    $this->cart->total_amount_excluding_taxes -= $cartItem->amount_excluding_taxes;
                    $this->cart->total_amount_including_taxes -= $cartItem->amount_including_taxes;
                    $this->cart->items_count--;

                    $cartItem->delete();
                    if ($this->cart->items_count === 0) {
                        $this->cart->delete();
                        $this->cart = null;
                    }
                }
                */

                $cartItem = new CartItem();

                break;
        }
        Cookie::queue('cart-items', $this->items->toJson());

        return $cartItem;
    }
}
