<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show (string $categorySlug, int $categoryId, string $slug, Product $product)
    {
        $product->load(['category:id,slug,label', 'references.images', 'references.main_image']);

        if ($categorySlug !== $product->category->slug || $slug !== $product->slug || $categoryId !== $product->category->id) {
            return redirect($product->url);
        }

        $breadcrumb = [
            ['label' => __('Categories'), 'url' => route('categories.index')],
            ['label' => $product->category->label, 'url' => $product->category->url],
            ['label' => $product->label]
        ];

        return view('products.show', compact('product', 'breadcrumb'));
    }
}
