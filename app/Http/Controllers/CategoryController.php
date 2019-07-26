<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show (string $slug, Category $category, Request $request, CategoryRepository $categoryRepository)
    {
        if ($slug !== $category->slug || $request->get('page') === 1) {
            return redirect($category->url);
        }

        $products = $categoryRepository->getProductsFor($category)->get();

        $filters = $products->isNotEmpty() ? $categoryRepository->getProductFields($category) : collect();
        $filterValues = $request->get('f', []);

        $currentPage = (int)$request->get('page', 1);
        $perPage = Category::PRODUCTS_PER_PAGE;

        return view('categories.show', compact('category', 'products', 'filters', 'filterValues', 'currentPage', 'perPage'));
    }

    public function index ()
    {

    }
}
