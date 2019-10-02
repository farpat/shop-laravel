<?php

namespace App\Services\Pdf;

use Exception;
use mikehaertl\wkhtmlto\Pdf as WkPdf;

abstract class Pdf
{
    protected $options = [];

    public function send (string $filename = null): bool
    {
        $pdf = $this->makePdfObject();
        return $pdf->send($filename);
    }

    private function makePdfObject ()
    {
        config(['app.webpack_port' => null]);
        $pdf = new WkPdf();
        foreach ($this->getPages() as $page) {
            $pdf->addPage($page);
        }

        return $pdf;
    }

    protected abstract function getPages (): array;

    public function save (): bool
    {
        $pdf = $this->makePdfObject();
        if (!method_exists($this, 'getFilePath')) {
            $class = get_class($this);
            throw new Exception("You must define getFilePath() method in << $class >>");
        }
        return $pdf->saveAs($this->getFilePath());
    }
}