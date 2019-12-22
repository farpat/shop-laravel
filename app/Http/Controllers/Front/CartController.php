<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Middleware\MustXmlHttpRequest;
use App\Http\Requests\{PurchaseRequest, StoreCartItemRequest, UpdateCartItemRequest};
use App\Repositories\ProductRepository;
use App\Services\Bank\{CartManager, StripeService};
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\{JsonResponse, Request};

class CartController extends Controller
{
    /**
     * @var CartManager
     */
    private $cartManager;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct (ProductRepository $productRepository)
    {
        $this->middleware(MustXmlHttpRequest::class)->only(['storeItem', 'updateItem', 'destroyItem']);
        $this->middleware(Authenticate::class)->only(['purchase', 'showPurchaseForm']);
        $this->middleware(function (Request $request, $next) {
            $this->cartManager = app(CartManager::class)->refresh($request->user());
            return $next($request);
        });

        $this->productRepository = $productRepository;
    }

    public function storeItem (StoreCartItemRequest $request)
    {
        $responseData = $this->cartManager->addItem(
            $request->input('quantity'),
            $this->productRepository->getReference($request->input('product_reference_id'))
        );

        return new JsonResponse($responseData);
    }

    public function updateItem (UpdateCartItemRequest $request, int $productReferenceId)
    {
        $responseData = $this->cartManager->updateItem(
            $request->input('quantity'),
            $this->productRepository->getReference($productReferenceId)
        );

        return new JsonResponse($responseData);
    }

    public function destroyItem (int $productReferenceId)
    {
        $responseData = $this->cartManager->deleteItem(
            $this->productRepository->getReference($productReferenceId)
        );

        return new JsonResponse($responseData);
    }

    public function showPurchaseForm ()
    {
        $cartItems = $this->cartManager->getItems();

        if (empty($cartItems)) {
            return redirect()
                ->route('home.index')
                ->with('error', __('Your cart is empty'));
        }

        return view('cart.purchase');
    }

    public function purchase (PurchaseRequest $request, StripeService $stripeService)
    {
        $totalToPay = $this->cartManager->getCart()->total_amount_including_taxes;

        $stripeService
            ->setToken($request->input('stripe_token'))
            ->charge($this->cartManager->getUser(), $totalToPay * 100);

        $this->cartManager->transformToBilling();

        return redirect()
            ->route('home.index')
            ->with('success', __('Your cart is purchased'));
    }
}
