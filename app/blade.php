<?php

use App\Repositories\NavigationRepository;
use Illuminate\Support\{HtmlString, ViewErrorBag};

function navigation ()
{
    return app(NavigationRepository::class);
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
            $liHtml .= "<li class=\"breadcrumb-item\"><a href=\"{$links[$i]['url']}\">{$links[$i]['label']}</a></li>";
        } else {
            $liHtml .= "<li class=\"breadcrumb-item active\" aria-current=\"page\">{$links[$i]['label']}</li>";
        }
    }

    return new HtmlString("<nav aria-label=\"breadcrumb\"><ol class=\"breadcrumb\">$liHtml</ol></nav>");
}

function get_form_store (ViewErrorBag $errorBag, array $old): HtmlString
{
    $errors = json_encode(array_map(function ($errors) {
        return $errors[0];
    }, $errorBag->getMessages()), JSON_FORCE_OBJECT);

    unset($old['_token']);
    $datas = json_encode($old, JSON_FORCE_OBJECT);

    return new HtmlString("window.formStore = { errors : $errors, datas : $datas}");
}

function get_asset (string $asset): string
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

        $return = $json->{$asset};
        return $return ?
            asset($return, config('app.env') === 'production') :
            '';
    }
}
