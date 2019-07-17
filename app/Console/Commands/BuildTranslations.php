<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BuildTranslations extends Command
{

    const JS_LANG = 'js-lang';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:build-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build translations in resources';

    /**
     * @return string
     */
    public function getDescription (): string
    {
        return $this->description . '/' . self::JS_LANG;
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        $this->deleteDirectoryIfExist(resource_path(self::JS_LANG));

        foreach ($this->getPathsInLangDirectory() as $path) {
            $files = $this->getLangFiles($path);

            foreach ($files as $file) {
                $this->compileTranslation($file);
            }
        }
    }

    private function compileTranslation (string $file)
    {
        $this->makeDirectoryIfNotExist($langPath = resource_path(self::JS_LANG));
        $compilationPath = $this->getCompilationPath($file, $langPath);

        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $data = json_encode(require($file));
        }
        else {
            ob_start();
            require($file);
            $ob = ob_get_clean();
            $data = json_encode(json_decode($ob));
        }

        file_put_contents($compilationPath, $data);
    }

    private function recursiveRemoveDirectory ($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                $this->recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }

    private function deleteDirectoryIfExist (string $path)
    {
        if (is_dir($path)) {
            $this->recursiveRemoveDirectory($path);
        }
    }

    private function makeDirectoryIfNotExist (string $path)
    {
        if (!is_dir($path)) {
            mkdir($path);
        }
    }

    private function getCompilationPath (string $file, string $langPath)
    {
        $langPathLen = strlen(resource_path('lang'));
        $path = $langPath . (substr($file, $langPathLen));

        $this->makeDirectoryIfNotExist(pathinfo($path, PATHINFO_DIRNAME));

        return str_replace('.php', '.json', $path);
    }

    private function getLangFiles (string $fileOrDirectory)
    {
        if (is_file($fileOrDirectory)) {
            return [$fileOrDirectory];
        }

        return array_slice(array_map(function (string $langFile) use ($fileOrDirectory) {
            return $fileOrDirectory . '/' . $langFile;
        }, scandir($fileOrDirectory)), 2);
    }

    /**
     * @return string[]
     */
    private function getPathsInLangDirectory ()
    {
        $files = [];

        foreach (array_slice(scandir(resource_path('lang')), 2) as $file) {
            if ($this->isLangDirectory($file)) {
                $files[] = resource_path('lang/' . $file);
            }
        }

        return $files;
    }

    private function isLangDirectory (string $path)
    {
        if (in_array($path, ['.', '..'])) {
            return false;
        }

        return true;
    }
}
