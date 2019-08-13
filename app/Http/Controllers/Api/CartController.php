<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\ProductReference;
use App\Repositories\CartRepository;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
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

    public function storeItem (StoreCartItemRequest $request)
    {
        $productReferenceId = $request->input('product_reference_id');
        $quantity = $request->input('quantity');

        $responseData = $this->cartRepository->addItem($quantity, $this->productRepository->getReference($productReferenceId));

        return new JsonResponse($responseData);
    }

    public function updateItem (UpdateCartItemRequest $request, int $productReferenceId)
    {
        $quantity = $request->input('quantity');

        $responseData = $this->cartRepository->updateItem($quantity, $this->productRepository->getReference($productReferenceId));

        return new JsonResponse($responseData);
    }

    public function destroyItem (int $productReferenceId)
    {
        $responseData = $this->cartRepository->deleteItem($this->productRepository->getReference($productReferenceId));

        return new JsonResponse($responseData);
    }
}
