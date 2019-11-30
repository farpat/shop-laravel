<?php

namespace App\Services\Bank;


use App\Models\{Cart, ProductReference, User};
use App\Repositories\{CartRepository, ProductRepository};
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class CartManager
{
    /**
     * @bool
     */
    private $isRefreshed = false;
    /**
     * @var Collection
     */
    private $items;
    /**
     * @var array
     */
    private $hydratedItems;
    /**
     * @var Cart|null
     */
    private $cart;
    /**
     * @var User
     */
    private $user;
    /**
     * @var CartRepository
     */
    private $cartRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct (CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function refresh (?User $user = null): void
    {
        $this->isRefreshed = true;
        if ($user) { //get cart and items from database
            $cart = $this->cartRepository->getCart($user);

            $items = $cart->items_count > 0 ?
                $this->cartRepository->getItems($cart) :
                [];

        } else { //get cart and items from cookie
            $cart = null;

            $items = $this->getCookieItems();
        }

        $this->cart = $cart;
        $this->items = collect($items);
        $this->user = $user;
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

        if ($this->user !== null) {
            $this->cartRepository->addItem($quantity, $productReference, $this->cart);
        } else {
            $this->persistCookie($this->items);
        }

        return $this->returnArrayForResponse($productReference, $quantity);
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

        if ($this->user !== null) {
            $this->cartRepository->updateItem($quantity, $productReference, $this->cart);
        } else {
            $this->persistCookie($this->items);
        }

        return $this->returnArrayForResponse($productReference, $quantity);
    }

    public function deleteItem (ProductReference $productReference): array
    {
        if ($this->items->get($productReference->id) === null) {
            throw new Exception(__('Product reference not present in cart'), 422);
        }

        $this->items->forget($productReference->id);

        if ($this->user !== null) {
            $this->cartRepository->deleteItem($productReference, $this->cart);
        } else {
            $this->persistCookie($this->items);
        }

        return $this->returnArrayForResponse($productReference);
    }

    public function mergeItemsOnDatabase (): void
    {
        $this->persistCookie(null);

        $this->cartRepository->mergeItemsOnDatabase($this->getCookieItems(), $this->getItems(false), $this->cart);
    }

    public function getItems ($hydrate = true): array
    {
        $items = $this->items->all();

        if (!$hydrate) {
            return $items;
        }

        if ($this->hydratedItems === null) {
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

            $this->hydratedItems = $items;
        }

        return $this->hydratedItems;
    }

    public function getCart (): ?Cart
    {
        return $this->cart;
    }

    public function getUser (): ?User
    {
        return $this->user;
    }

    public function updateCartOnOrderedStatus ()
    {
        $this->cartRepository->updateCartOnOrderedStatus($this->cart);
    }

    public function isRefreshed (): bool
    {
        return $this->isRefreshed;
    }
}