<?php

namespace App\Http\Controllers;

use App\Repositories\{CartRepository, CategoryRepository, ModuleRepository, ProductRepository};

class HomeController extends Controller
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    public function __construct (ProductRepository $productRepository, CategoryRepository $categoryRepository, ModuleRepository $moduleRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->moduleRepository = $moduleRepository;
    }

    public function index ()
    {
        $products = $this->productRepository->getProductsInHome();

        $categories = $this->categoryRepository->getCategoriesInHome();

        $carouselParameter = $this->moduleRepository->getParameter('home', 'carousel');
        $slides = collect($carouselParameter->value ?? []);

        $elementsParameter = $this->moduleRepository->getParameter('home', 'elements');
        $elements = collect($elementsParameter->value ?? []);

        return view('home.index', compact('products', 'categories', 'elements', 'slides'));
    }
}
