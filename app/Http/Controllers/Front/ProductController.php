<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function show (string $categorySlug, int $categoryId, string $slug, Product $product, ProductRepository $productRepository)
    {
        $product->load(['category:id,slug,label', 'references.main_image', 'references.images', 'references.product.category']);

        if ($categorySlug !== $product->category->slug || $slug !== $product->slug || $categoryId !== $product->category->id) {
            return redirect($product->url);
        }

        $productFields = $productRepository->getProductFields($product);

        $breadcrumb = [
            ['label' => trans_choice('category', 2), 'url' => route('categories.index')],
            ['label' => $product->category->label, 'url' => $product->category->url],
            ['label' => $product->label]
        ];

        return view('products.show', compact('product', 'breadcrumb', 'productFields'));
    }
}
