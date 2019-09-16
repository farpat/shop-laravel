<?php

namespace App\Http\Controllers;

use App\Http\Middleware\MustXmlHttpRequest;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function __construct (ProductRepository $productRepository)
    {
        $this->middleware(MustXmlHttpRequest::class)->except(['purchase']);
        $this->middleware(Authenticate::class)->only(['purchase']);
        $this->middleware(function (Request $request, $next) {
            $this->cartRepository = app(CartRepository::class);
            return $next($request);
        });
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

    public function purchase ()
    {
        $cartItems = view()->shared('cartItems');
        if (empty($cartItems)) {
            return redirect()->route('home.index')->with('danger', __('Your cart is empty'));
        }
        return view('cart.purchase');
    }
}
