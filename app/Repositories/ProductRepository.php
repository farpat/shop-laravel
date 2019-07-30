<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductField;
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
            return Product::query()->whereIn('id', $productIds->value)->get();
        }

        return collect();
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
}
