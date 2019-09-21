<?php

namespace App\Http\Middleware;

use App\Repositories\ModuleRepository;
use App\Services\Bank\CartManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\View\Factory as View;

class AddVariablesOnView
{
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;
    /**
     * @var View
     */
    private $view;
    /**
     * @var CartManager
     */
    private $cartManager;

    public function __construct (ModuleRepository $moduleRepository, CartManager $cartManager, View $view)
    {
        $this->moduleRepository = $moduleRepository;
        $this->view = $view;
        $this->cartManager = $cartManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        $this->view->share([
            'currency'  => $this->moduleRepository->getParameter('home', 'currency')->value,
            'cartItems' => $this->cartManager->getItems(),
            'user' => $request->user()
        ]);

        return $next($request);
    }
}
