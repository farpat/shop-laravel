<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;

class NavigationRepository implements Htmlable
{
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    /**
     * @var array
     */
    private $resources = [];

    /**
     * @var string
     */
    private $currentUrl;

    public function __construct (ModuleRepository $moduleRepository, UrlGenerator $url)
    {
        $this->moduleRepository = $moduleRepository;
        $this->currentUrl = $url->current();
    }

    public function toHtml ()
    {
        if (!$navigation = $this->moduleRepository->getParameter('home', 'navigation')) {
            return '';
        }

        $this->setResources($navigation->value);

        $html = '';

        foreach ($navigation->value as $key => $link1) {
            if (is_int($key)) {
                $html .= $this->renderLink1($link1);
            } else {
                $html .= $this->renderLinks2($key, $link1);
            }
        }

        return $html;
    }

    private function setResources (array $links)
    {
        $resources = [];

        foreach ($links as $key => $link1) {
            if (is_int($key)) {
                [$model, $id] = explode(':', $link1);
                $resources[$model][$id] = true;
            } else {
                [$model, $id] = explode(':', $key);
                $resources[$model][$id] = true;

                foreach ($link1 as $link2) {
                    [$model, $id] = explode(':', $link2);
                    $resources[$model][$id] = true;
                }
            }
        }

        foreach ($resources as $model => $ids) {
            $ids = array_keys($ids);
            if ($model === Product::class) {
                $resources[$model] = Product::query()->with(['category:id,slug'])->whereIn('id', $ids)->get()->keyBy('id');
            }
            elseif ($model === Category::class) {
                $resources[$model] = Category::query()->whereIn('id', $ids)->get()->keyBy('id');
            }
        }

        $this->resources = $resources;
    }

    private function renderLink1 (string $link1)
    {
        $resource = $this->getResource($link1);

        $activeClass = $resource->url === $this->currentUrl ? ' active' : '';

        return "<li class=\"nav-item\"><a class=\"nav-link{$activeClass}\" href=\"{$resource->url}\">{$resource->label}</a></li>";

    }

    /**
     * @param string $link1
     *
     * @return Product|Category
     * @throws Exception
     */
    private function getResource (string $link1)
    {
        [$model, $id] = explode(':', $link1);

        if (!$resource = $this->resources[$model]->get($id)) {
            throw new Exception("The model << $model >> is not menuable");
        }

        return $resource;
    }

    private function renderLinks2 (string $link, array $links)
    {
        $resource = $this->getResource($link);


        $begin = "<li class=\"nav-item dropdown\"><a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"dropdown-{$resource->slug}\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">{$resource->label}</a><div class=\"dropdown-menu\" aria-labelledby=\"dropdown-{$resource->slug}\">";

        $itemsHtml = array_reduce($links, function ($acc, $link) {
            $acc .= $this->renderLink2($link);
            return $acc;
        });

        $end = "</div></li>";

        return $begin . $itemsHtml . $end;
    }

    private function renderLink2 (string $link)
    {
        $resource = $this->getResource($link);

        $activeClass = $resource->url === $this->currentUrl ? ' active' : '';

        return "<a class=\"dropdown-item{$activeClass}\" href=\"{$resource->url}\">{$resource->label}</a>";
    }
}
