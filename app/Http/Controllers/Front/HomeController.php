<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\{CategoryRepository, ModuleRepository, ProductRepository};
use Illuminate\Http\Request;

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
        $elementsToDisplayInHomepage = $this->moduleRepository->getParameter('home', 'display')->value;

        return view('home.index', compact('elementsToDisplayInHomepage'));
    }

    public function search (Request $request)
    {
        $term = $request->input('q');

        if ($term === null) {
            return [];
        }

        $categories = $this->categoryRepository->search($term);
        $products = $this->productRepository->search($term);

        return $categories->merge($products);
    }
}
