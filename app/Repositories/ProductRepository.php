<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductField;
use App\Models\ProductReference;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
            ->with(['product.taxes', 'product.category'])
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
     * @return ProductReference[]|Collection
     */
    public function getReferences (?array $productReferenceIds = null)
    {
        $query = ProductReference::query()->with(['product.taxes', 'product.category', 'main_image']);

        if ($productReferenceIds) {
            $query->whereIn('id', $productReferenceIds);
        }

        return $query->get()->keyBy('id');
    }

    public function search (string $term): Collection
    {
        $domain = config('app.url');

        return DB::query()
            ->selectRaw('
            p.id, p.label, i.url_thumbnail as image, 
            CONCAT("' . $domain . '", "/categories/", c.slug, "-", c.id, "/", p.slug, "-", p.id) as url,
            MIN(pr.unit_price_including_taxes) as min_unit_price_including_taxes')
            ->fromRaw('products p')
            ->leftJoin(DB::raw('images i'), DB::raw('p.main_image_id'), '=', DB::raw('i.id'))
            ->leftJoin(DB::raw('categories c'), DB::raw('p.category_id'), '=', DB::raw('c.id'))
            ->leftJoin(DB::raw('product_references pr'), DB::raw('p.id'), '=', DB::raw('pr.product_id'))
            ->whereRaw('p.label like :term', ['term' => "%$term%"])
            ->limit(5)
            ->groupBy(DB::raw('p.id'))
            ->get();
    }
}
