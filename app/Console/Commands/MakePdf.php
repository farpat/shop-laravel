<?php

namespace App\Console\Commands;

use App\Pdf\BillingPdf;
use Composer\Autoload\ClassLoader;
use Composer\Composer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakePdf extends Command
{
    const PDF_STUB = __DIR__ . '/../../Services/Pdf/Pdf.stub';

    const NAMESPACE = 'App\Pdf';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:pdf';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new PDF class';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        $pdfDirectory = app_path(str_replace('App\\', '', self::NAMESPACE));

        if (!File::exists($pdfDirectory)) {
            File::makeDirectory($pdfDirectory);
        }

        $className = $this->getClassName($this->argument('name'));

        $classPath = $this->getClassPath($pdfDirectory, $className);
        if (File::exists($classPath)) {
            $this->error(sprintf('The PDF class << %s\\%s >> exists!', self::NAMESPACE, $className));
        } else {
            $this->createPdfClass($className, $classPath);
            $this->info(sprintf('The PDF class << %s\\%s >> is created.', self::NAMESPACE, $className));
        }
    }

    protected function getClassName (string $name)
    {
        $name = Str::endsWith($name, 'pdf') ?
            substr($name, 0, -3) . '-pdf' :
            $name . '-pdf';

        $name = ucfirst(Str::camel($name));

        return $name;
    }

    protected function getClassPath (string $pdfDirectory, string $className)
    {
        return $pdfDirectory . '/' . $className . '.php';
    }

    protected function createPdfClass (string $className, string $classPath)
    {
        $fileContent = file_get_contents(self::PDF_STUB);
        $fileContent = str_replace('DummyClass', $className, $fileContent);
        $fileContent = str_replace('DummyNamespace', self::NAMESPACE, $fileContent);
        file_put_contents($classPath, $fileContent);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments ()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
