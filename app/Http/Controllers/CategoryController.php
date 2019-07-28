<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show (string $slug, Category $category, Request $request, CategoryRepository $categoryRepository)
    {
        $currentPage = $request->get('page');
        if ($slug !== $category->slug || $currentPage === 1 || (!is_null($currentPage) && !is_numeric($currentPage))) {
            return redirect($category->url);
        }

        $products = $categoryRepository->getProductsFor($category)->get();
        $currentPage = $currentPage ?? 1;
        $filters = $products->isNotEmpty() ? $categoryRepository->getProductFields($category) : collect();
        $filterValues = $request->get('f', []);

        $perPage = Category::PRODUCTS_PER_PAGE;

        return view('categories.show', compact('category', 'products', 'filters', 'filterValues', 'currentPage', 'perPage'));
    }

    public function index ()
    {

    }
}
