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

        $currentPage = (int)$request->get('page', 1);
        $perPage = Category::PRODUCTS_PER_PAGE;
        $products = $categoryRepository->getProductsFor($category)->get();
        $filters = $products->isNotEmpty() ? $categoryRepository->getProductFields($category) : collect();

        return view('categories.show', compact('category', 'products', 'filters', 'currentPage', 'perPage'));
    }

    public function index ()
    {

    }
}
