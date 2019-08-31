<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductField;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

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

    /**
     * @param Category[] $parents
     *
     * @return HtmlString
     * @throws \Exception
     */
    public function toHtml (array $parents): HtmlString
    {
        $string = '';
        if (!empty($parents)) {
            foreach ($parents as $parent) {
                $children = $this->getChildren($parent)->all();
                $img = $parent->image ?
                    "<img src=\"{$parent->image->url_thumbnail}\" alt=\"{$parent->image->alt_thumbnail}\">" :
                    "<img src=\"https://via.placeholder.com/80x32\">";

                $string .= <<<HTML
                <div class="media">
                    <a class="media-link" href="{$parent->url}">
                        $img
                    </a>
                    <div class="media-body">
                        <h2><a href="{$parent->url}">{$parent->label}</a></h2>
                        {$this->toHtml($children)}
                    </div>
                </div>
                HTML;
            }
        }

        return new HtmlString($string);
    }

    public function getRootCategories (): array
    {
        return Category::query()
            ->where(DB::raw('LENGTH(nomenclature) - LENGTH(REPLACE(nomenclature,".","")) + 1'), 1)
            ->with(['image'])
            ->get()
            ->all();
    }

    public function getCategoriesInHome (): Collection
    {
        if ($categoryIds = $this->moduleRepository->getParameter('home', 'categories')) {
            return Category::query()
                ->with('image')
                ->whereIn('id', $categoryIds->value)
                ->get();
        }

        return collect();
    }

    public function getProductsFor (Category $category): Builder
    {
        return Product::query()
            ->with(['category', 'main_image', 'references'])
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
            ->whereRaw('LENGTH(nomenclature) - LENGTH(REPLACE(nomenclature, "' . Category::BREAKING_POINT . '", "")) + 1 = ?', [$category->level + 1])
            ->with(['image'])
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

    public function search (string $term): Collection
    {
        $domain = config('app.url');

        return DB::query()
            ->selectRaw('
            c.id, c.label, i.url_thumbnail as image, 
            CONCAT("' . $domain . '", "/categories/", c.slug, "-", c.id) as url')
            ->fromRaw('categories c')
            ->leftJoin(DB::raw('images i'), DB::raw('c.image_id'), '=', DB::raw('i.id'))
            ->whereRaw('c.label like :term', ['term' => "%$term%"])
            ->groupBy(DB::raw('c.id'))
            ->limit(2)
            ->get();
    }
}
