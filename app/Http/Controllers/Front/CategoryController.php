<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct (CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function show (string $slug, Category $category, Request $request)
    {
        $category->load(['image']);

        $currentPage = $request->get('page');
        if ($slug !== $category->slug || $currentPage === 1 || (!is_null($currentPage) && !is_numeric($currentPage))) {
            return redirect($category->url);
        }

        $breadcrumb = [
            ['label' => trans_choice('category', 2), 'url' => route('categories.index')],
            ['label' => $category->label]
        ];

        $products = $this->categoryRepository->getProductsFor($category)->get();

        $filters = $products->isNotEmpty() ? $this->categoryRepository->getProductFields($category) : collect();
        $filterValues = $request->get('f', []);

        $perPage = Category::PRODUCTS_PER_PAGE;

        return view('categories.show', compact('category', 'products', 'filters', 'filterValues', 'currentPage', 'perPage', 'breadcrumb'));
    }

    public function index ()
    {
        $html = $this->categoryRepository->toHtml($this->categoryRepository->getRootCategories());

        return view('categories.index', compact('html'));
    }
}
