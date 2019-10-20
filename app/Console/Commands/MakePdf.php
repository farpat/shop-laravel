<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakePdf extends Command
{
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
        if (!File::exists(app_path('Pdf'))) {
            File::makeDirectory(app_path('Pdf'));
        }

        $className = $this->getClassNameWithoutNamespace($this->argument('name'));

        $path = $this->getPath($className);
        if (File::exists($path)) {
            $this->error("The PDF class << App\Pdf\\$className >> exists!");
        } else {
            $this->createPdfClass($className, $path);
            $this->info("The PDF class << App\Pdf\\$className >> is created.");
        }
    }

    protected function getClassNameWithoutNamespace (string $name)
    {
        $name = Str::endsWith($name, 'pdf') ?
            substr($name, 0, -3) . '-pdf' :
            $name . '-pdf';

        $name = ucfirst(Str::camel($name));

        return $name;
    }

    protected function getPath (string $name)
    {
        return app_path("Pdf/$name.php");
    }

    protected function createPdfClass (string $className, string $path)
    {
        $fileContent = file_get_contents(app_path('Services/Pdf/Pdf.stub'));
        $fileContent = str_replace('DummyClass', $className, $fileContent);
        file_put_contents($path, $fileContent);
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
