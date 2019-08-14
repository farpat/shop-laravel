<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductField;
use App\Models\ProductReference;
use Illuminate\Support\Collection;

class ProductRepository
{

    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    public function __construct (ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function getProductsInHome (): Collection
    {
        if ($productIds = $this->moduleRepository->getParameter('home', 'products')) {
            return Product::query()
                ->with('category', 'main_image')
                ->whereIn('id', $productIds->value)
                ->get();
        }

        return collect();
    }

    /**
     * @param int $productReferenceId
     *
     * @return ProductReference
     */
    public function getReference (int $productReferenceId)
    {
        return ProductReference::query()
            ->with('product.taxes', 'product.category')
            ->findOrFail($productReferenceId);
    }

    /**
     * @param Product $product
     *
     * @return Collection|ProductField[]
     */
    public function getProductFields (Product $product): Collection
    {
        if ($firstReference = $product->references->get(0)) {
            $productFieldIds = array_keys($firstReference->filled_product_fields);
            return ProductField::query()->whereIn('id', $productFieldIds)->get()->keyBy('id');
        }

        return collect();
    }

    /**
     * @param array|null $productReferenceIds
     *
     * @return ProductReference[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getReferences (?array $productReferenceIds = null)
    {
        $query = ProductReference::query()
            ->with(['product', 'product.category:id,slug,label', 'product.taxes']);

        if ($productReferenceIds) {
            $query->whereIn('id', $productReferenceIds);
        }

        return $query->get()->keyBy('id');


    }
}
