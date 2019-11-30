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

    public function toHtml (): string
    {
        if (!$navigation = $this->moduleRepository->getParameter('home', 'navigation')) {
            return '';
        }

        $this->setResources($links = (array)$navigation->value);

        $html = '';

        foreach ($links as $key => $link1) {
            $html = is_int($key) ?
                $html . $this->renderLink1($link1) :
                $html . $this->renderLinks2($key, $link1);
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

            switch ($model) {
                case Product::class:
                    $resources[$model] = Product::query()->with(['category:id,slug'])->whereIn('id', $ids)->get()->keyBy('id');
                    break;
                case Category::class:
                    $resources[$model] = Category::query()->whereIn('id', $ids)->get()->keyBy('id');
                    break;
            }
        }

        $this->resources = $resources;
    }

    private function renderLink1 (string $link1): string
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
    private function getResource (string $link1): Model
    {
        [$model, $id] = explode(':', $link1);

        if (!$resource = $this->resources[$model]->get($id)) {
            throw new Exception("The model << $model >> is not menuable");
        }

        return $resource;
    }

    private function renderLinks2 (string $link, array $links): string
    {
        $resource = $this->getResource($link);

        $itemsHtml = array_reduce($links, function ($acc, $link) {
            $acc .= $this->renderLink2($link);
            return $acc;
        });

        return <<<HTML
<li class="nav-item dropdown">
    <button class="nav-link btn btn-link dropdown-toggle" id="dropdown-{$resource->slug}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {$resource->label}
    </button>
    
    <div class="dropdown-menu" aria-labelledby="dropdown-{$resource->slug}">
        {$itemsHtml}
    </div>
</li>
HTML;
    }

    private function renderLink2 (string $link): string
    {
        $resource = $this->getResource($link);

        $activeClass = $resource->url === $this->currentUrl ? ' active' : '';

        return <<<HTML
<a class="dropdown-item{$activeClass}" href="{$resource->url}">{$resource->label}</a>
HTML;
    }
}
