<?php

namespace App\Http\Middleware;

use App\Repositories\CartRepository;
use App\Repositories\ModuleRepository;
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
     * @var CartRepository
     */
    private $cartRepository;
    /**
     * @var View
     */
    private $view;

    public function __construct (ModuleRepository $moduleRepository, CartRepository $cartRepository, View $view)
    {
        $this->moduleRepository = $moduleRepository;
        $this->cartRepository = $cartRepository;
        $this->view = $view;
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
            'cartItems' => $this->cartRepository->getItems()
        ]);

        return $next($request);
    }
}
