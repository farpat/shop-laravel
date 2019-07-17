<?php

use Illuminate\Support\{Str, ViewErrorBag};

/**
 * @param string $prefixPath Nom de la route
 *
 * @param string $echo
 *
 * @return string
 */
function is_active (string $prefixPath, string $echo): string
{
    if (Str::startsWith(request()->path(), $prefixPath)) {
        return $echo;
    }

    return '';
}

/**
 * @param ViewErrorBag $errorBag
 * @param array $old
 *
 * @return string
 */
function get_form_store (ViewErrorBag $errorBag, array $old): string
{
    $errors = json_encode(array_map(function ($errors) {
        return $errors[0];
    }, $errorBag->getMessages()));

    unset($old['_token']);
    $datas = json_encode($old);

    return "window.formStore = { errors : $errors, datas : $datas}";
}

/**
 * @param string $asset
 *
 * @return string
 */
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
