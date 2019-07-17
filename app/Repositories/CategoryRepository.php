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
            return Category::whereIn('id', $categoryIds->value)->get();
        }

        return collect();
    }
}
