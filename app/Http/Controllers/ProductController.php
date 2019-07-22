<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function show (string $categorySlug, int $categoryId, string $slug, Product $product)
    {
        $product->load(['category:id,slug']);

        if ($categorySlug !== $product->category->slug || $slug !== $product->slug || $categoryId !== $product->category->id) {
            return redirect($product->url);
        }

        return view('products.show', compact('product'));
    }
}
