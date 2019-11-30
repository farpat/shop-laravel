<?php

namespace App\Services\Pdf;

use mikehaertl\wkhtmlto\Pdf as WkPdf;

abstract class Pdf
{
    protected function beforeMake() {
        config(['app.webpack_port' => null]);
    }

    public function send (string $filename = null): bool
    {
        $pdf = $this->makePdfObject();
        if ($pdf->send($filename)) {
            throw new PdfException($pdf->getError());
        }
    }


    private function makePdfObject ()
    {
        $this->beforeMake();

        $pdf = new WkPdf();
        $options = $this->getOptions();
        if (!empty($options)) {
            $pdf->setOptions($this->getOptions());
        }
        foreach ($this->getPages() as $page) {
            $pdf->addPage($page);
        }

        return $pdf;
    }

    protected abstract function getOptions (): array;

    protected abstract function getPages (): array;

    public function save (): bool
    {
        $pdf = $this->makePdfObject();
        $filePath = $this->getFilePath();
        if ($filePath === null) {
            $class = get_class($this);
            throw new PdfException("You must define getFilePath() method in << $class >>");
        }

        if (!$pdf->saveAs($filePath)) {
            throw new PdfException($pdf->getError());
        }
    }

    protected function getFilePath (): ?string
    {
        return null;
    }
}