<?php

use App\Repositories\ModuleRepository;
use App\Repositories\NavigationRepository;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\{Arr, HtmlString, MessageBag, ViewErrorBag};

function parameter ($moduleLabel, $parameterLabel)
{
    $moduleParameter = app(ModuleRepository::class)->getParameter($moduleLabel, $parameterLabel);
    if ($moduleParameter === null) {
        throw new Exception("Module parameter << $moduleLabel.$parameterLabel >> doesn't not exists!");
    }

    return $moduleParameter->value;
}

function navigation (): Htmlable
{
    return app(NavigationRepository::class);
}

function is_active (string $url): string
{
    return url()->current() === $url ? 'active' : '';
}

function breadcrumb (array $links): HtmlString
{
    $linksCount = count($links);

    if ($linksCount === 0) {
        return new HtmlString('');
    }

    $liHtml = '';
    for ($i = 0; $i < $linksCount; $i++) {
        if ($i + 1 < $linksCount) {
            $liHtml .= <<<HTML
<li class="breadcrumb-item"><a href="{$links[$i]['url']}">{$links[$i]['label']}</a></li>
HTML;
        } else {
            $liHtml .= <<<HTML
<li class="breadcrumb-item active" aria-current="page">{$links[$i]['label']}</li>
HTML;
        }
    }

    return new HtmlString("<nav aria-label=\"breadcrumb\"><ol class=\"breadcrumb\">$liHtml</ol></nav>");
}

/**
 * @param ViewErrorBag|MessageBag $errorBag
 * @param array $old
 *
 * @return HtmlString
 */
function get_form_store ($errorBag, array $old): HtmlString
{
    //errors
    $errors = [];
    foreach ($errorBag->getMessages() as $key => $message) {
        Arr::set($errors, $key, $message[0]);
    }
    $errors = json_encode($errors, JSON_FORCE_OBJECT);

    //datas
    unset($old['_token']);
    $datas = json_encode($old, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK);

    return new HtmlString("window._Store = { errors : $errors, datas : $datas, rules : {}}");
}

function get_asset (string $asset, bool $absolute = false): string
{
    static $json;

    if ($webpackDevServerPort = config('app.webpack_port')) {
        return (substr($asset, -4) !== '.css') ?
            'http://localhost:' . $webpackDevServerPort . '/assets/' . $asset :
            '';
    } else {

        if (!$json) {
            $json = json_decode(file_get_contents(public_path('assets/manifest.json')));
        }

        $return = $json->{$asset} ?? null;

        if ($return === null) {
            throw new \Exception("Impossible to retrieve the asset << $asset >>");
        }

        return $absolute ?
            public_path($return) :
            asset($return, config('app.env') === 'production');
    }
}
