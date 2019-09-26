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
        $this->middleware(MustXmlHttpRequest::class)->except(['purchase', 'showPurchaseForm']);
        $this->middleware(Authenticate::class)->only(['purchase', 'showPurchaseForm']);
        $this->middleware(function (Request $request, $next) {
            $this->cartManager = app(CartManager::class);
            $this->cartManager->refresh($request->user());
            return $next($request);
        });
        $this->productRepository = $productRepository;
    }

    public function storeItem (StoreCartItemRequest $request)
    {
        $productReferenceId = $request->input('product_reference_id');
        $quantity = $request->input('quantity');

        $responseData = $this->cartManager->addItem($quantity, $this->productRepository->getReference($productReferenceId));

        return new JsonResponse($responseData);
    }

    public function updateItem (UpdateCartItemRequest $request, int $productReferenceId)
    {
        $quantity = $request->input('quantity');

        $responseData = $this->cartManager->updateItem($quantity, $this->productRepository->getReference($productReferenceId));

        return new JsonResponse($responseData);
    }

    public function destroyItem (int $productReferenceId)
    {
        $responseData = $this->cartManager->deleteItem($this->productRepository->getReference($productReferenceId));

        return new JsonResponse($responseData);
    }

    public function showPurchaseForm ()
    {
        $cartItems = $this->cartManager->getItems();

        if (empty($cartItems)) {
            return redirect()
                ->route('home.index')
                ->with('danger', __('Your cart is empty'));
        }

        return view('cart.purchase');
    }

    public function purchase (PurchaseRequest $request, StripeService $stripeService)
    {
        $cart = $this->cartManager->getCart();
        $totalToPay = $cart->total_amount_including_taxes;

        dd('720 360,05 €', $totalToPay);

        $stripeService->charge($this->cartManager->getUser(), $totalToPay * 100, $request->input('token'));
    }
}
