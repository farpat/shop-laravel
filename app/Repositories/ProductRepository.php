<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
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

    public function getProductsForCategory (Category $category): Builder
    {
        return Product::query()->whereHas('category', function (Builder $query) use ($category) {
            $query
                ->where('nomenclature', 'like', $category->nomenclature . '.%')
                ->orWhere('nomenclature', $category->nomenclature);
        });
    }
}
