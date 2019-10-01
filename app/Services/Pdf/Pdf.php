<?php

namespace App\Services\Pdf;

use Illuminate\Support\Facades\Storage;

abstract class Pdf
{
    private function beforeAction() {
        config(['app.webpack_port' => null]);
    }

    public function send (string $filename = null): bool
    {
        $this->beforeAction();
        $pdf = new \mikehaertl\wkhtmlto\Pdf($this->render());
        return $pdf->send($filename);
    }

    protected abstract function render ();

    public function save (string $filepath): bool
    {
        $this->beforeAction();
        $pdf = new \mikehaertl\wkhtmlto\Pdf($this->render());
        return $pdf->saveAs($filepath);
    }


}