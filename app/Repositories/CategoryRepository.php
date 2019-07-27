<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CategoryRepository
{

    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    public function __construct (ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }


    public function getCategoriesInHome (): Collection
    {
        if ($categoryIds = $this->moduleRepository->getParameter('home', 'categories')) {
            return Category::query()
                ->whereIn('id', $categoryIds->value)
                ->get();
        }

        return collect();
    }

    public function getProductsFor (Category $category): Builder
    {
        return Product::query()
            ->with(['references'])
            ->whereHas('category', function (Builder $query) use ($category) {
                $query
                    ->where('nomenclature', 'like', $category->nomenclature . '.%')
                    ->orWhere('nomenclature', $category->nomenclature);
            });
    }

    public function getProductFields (Category $category): Collection
    {
        if ($parentsBuilder = $this->getParents($category)) {
            $ids = $parentsBuilder->pluck('id');
        } else {
            $ids = collect();
        }

        $ids->push($category->id);

        return ProductField::query()
            ->whereIn('category_id', $ids)
            ->get()
            ->keyBy('id');
    }

    private function getParents (Category $category): ?Builder
    {
        if ($category->level === 1) {
            return null;
        }

        $nomenclatures = [];
        $categoryNomenclature = $category->nomenclature;
        for ($i = 0; $i < strlen($categoryNomenclature); $i++) {
            if ($categoryNomenclature[$i] === Category::BREAKING_POINT) {
                $nomenclatures[] = substr($categoryNomenclature, 0, $i - 1);
            }
        }

        return Category::query()->whereIn('nomenclature', $nomenclatures);
    }

    public function getChildren (Category $category): Collection
    {
        if ($category->is_last) {
            return collect();
        }

        return Category::query()
            ->where('nomenclature', 'LIKE', $category->nomenclature . '.%')
            ->whereRaw('LENGTH(nomenclature) - LENGTH(REPLACE(nomenclature, "' . Category::BREAKING_POINT . '", "")) = :level', [$category->level])
            ->get();
    }

    public function setProductFields (Category $category, array $productFieldsRequestData): Collection
    {
        $productFields = collect();

        foreach ($productFieldsRequestData as $productFieldsRequestDatum) {
            if (isset($productFieldsRequestDatum['deleted'])) {
                $productFieldIdsToDestroy[] = $productFieldsRequestDatum['id'];
            } else {
                if (isset($productFieldsRequestDatum['updated'])) {
                    $productField = ProductField::query()->find($productFieldsRequestDatum['id']);
                } else {
                    $productField = new ProductField();
                }

                $productField
                    ->fill([
                        'type'        => $productFieldsRequestDatum['type'],
                        'label'       => $productFieldsRequestDatum['label'],
                        'is_required' => $productFieldsRequestDatum['is_required'],
                        'category_id' => $category->id,
                    ])
                    ->save();

                $productFields->push($productField);
            }
        }

        return $productFields;
    }
}
