<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show (string $slug, Category $category, Request $request, ProductRepository $productRepository)
    {
        if ($slug !== $category->slug || $request->get('page') == 1) {
            return redirect($category->url);
        }

        $products = $productRepository->getProductsForCategory($category)->paginate(Category::PRODUCTS_PER_PAGE);

        return view('categories.show', compact('category', 'products'));
    }

    public function index ()
    {

    }
}
