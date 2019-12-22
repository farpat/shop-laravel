<?php

namespace App\ViewComposers;

use App\Repositories\ModuleRepository;
use App\Services\Bank\CartManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartStoreViewComposer
{
    /**
     * @var CartManager
     */
    private $cartManager;
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    public function __construct (CartManager $cartManager, ModuleRepository $moduleRepository)
    {
        $this->cartManager = $cartManager;
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose (View $view)
    {
        if (!$this->cartManager->isRefreshed()) {
            $this->cartManager->refresh(Auth::user());
        }

        $currency = $this->moduleRepository->getParameter('billing', 'currency')->value;
        $view
            ->with('cartItems', $this->cartManager->getItems())
            ->with('currency', $this->moduleRepository->getParameter('billing', 'currency')->value);
    }
}