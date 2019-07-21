<?php

namespace App\Repositories;

use App\Models\Category;
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

    public function getChildren (Category $category): Collection
    {
        if ($category->is_last) {
            return null;
        }

        /*
         ROUND (
        (
            LENGTH(description)
            - LENGTH( REPLACE ( description, "value", "") )
        ) / LENGTH("value")
        */

        //TOTO.TUTU

        return Category::query()
            ->where('nomenclature', 'LIKE', $category->nomenclature . '.%')
            ->whereRaw('LENGTH(nomenclature) - LENGTH(REPLACE(nomenclature, ".", "")) = :level', [$category->level])
            ->get();
    }
}
