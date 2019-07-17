<?php

namespace App\Http\Controllers;

use App\Repositories\{CategoryRepository, ModuleRepository, ProductRepository};

class HomeController extends Controller
{
    public function index (ProductRepository $productRepository, CategoryRepository $categoryRepository, ModuleRepository $moduleRepository)
    {
        $products = $productRepository->getProductsInHome();

        $categories = $categoryRepository->getCategoriesInHome();

        $slides = collect();
        if ($carouselParameter = $moduleRepository->getParameter('home', 'carousel')) {
            $slides = collect($carouselParameter->value);
        }


        $elements = collect();
        if ($elementsParameter = $moduleRepository->getParameter('home', 'elements')) {
            $elements = collect($elementsParameter->value);
        }

        return view('home.index', compact('products', 'categories', 'elements', 'slides'));
    }
}
