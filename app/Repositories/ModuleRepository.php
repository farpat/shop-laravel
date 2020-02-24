<?php

namespace App\Repositories;


use App\Models\{Module, ModuleParameter};
use App\Support\StringUtility;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\{Builder, ModelNotFoundException};

class ModuleRepository
{
    public function __construct ()
    {
        \Debugbar::info('ModuleRepository construct called!');
    }

    private $cache = [];

    public function createModule (string $moduleLabel, bool $isActive = false, string $description = null): Module
    {
        $module = new Module([
            'label'       => $moduleLabel,
            'is_active'   => $isActive,
            'description' => $description
        ]);

        $module->save();

        return $module;
    }

    public function getParameter (string $moduleLabel, string $parameterLabel): ?ModuleParameter
    {
        if (isset($this->cache[$moduleLabel][$parameterLabel])) {
            return $this->cache[$moduleLabel][$parameterLabel];
        }

        /** @var ModuleParameter|null $moduleParameter */
        $moduleParameter = ModuleParameter::query()
            ->whereHas('module', function (Builder $query) use ($moduleLabel) {
                $query->where('label', $moduleLabel);
            })
            ->where('label', $parameterLabel)
            ->first();

        if ($moduleParameter) {
            $this->transformValueField($moduleParameter);
        }

        $this->cache[$moduleLabel][$parameterLabel] = $moduleParameter;

        return $moduleParameter;
    }

    private function transformValueField (ModuleParameter $moduleParameter)
    {
        if ($jsonDecoded = StringUtility::jsonDecode($moduleParameter->value)) {
            $moduleParameter->value = $jsonDecoded;
        }
    }

    public function updateParameter (string $moduleLabel, string $parameterLabel, $value, ?string $description = null)
    {
        $moduleParameter = ModuleParameter::query()
            ->whereHas('module', function (Builder $query) use ($moduleLabel) {
                $query->where('label', $moduleLabel);
            })
            ->where('label', $parameterLabel)
            ->first();

        if ($moduleParameter === null) {
            throw new ModelNotFoundException("Module parameter << $moduleLabel.$parameterLabel >> doesn't not exists!");
        }

        $moduleParameter->update([
            'value'       => (is_array($value) || $value instanceof Jsonable) ? json_encode($value) : $value,
            'description' => $description
        ]);


        $this->setCache($moduleLabel, $parameterLabel, $moduleParameter);

        return $moduleParameter;
    }

    private function setCache (string $moduleLabel, string $parameterLabel, ModuleParameter $moduleParameter)
    {
        $this->cache[$moduleLabel][$parameterLabel] = $moduleParameter;
    }

    public function createParameter (string $moduleLabel, string $parameterLabel, $value, string $description = null): ModuleParameter
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $moduleParameter = ModuleParameter::create([
            'label'       => $parameterLabel,
            'value'       => $value,
            'description' => $description,
            'module_id'   => $this->getModule($moduleLabel)->id
        ]);

        $this->setCache($moduleLabel, $parameterLabel, $moduleParameter);

        return $moduleParameter;
    }

    public function getModule (string $moduleLabel): Module
    {
        $module = Module::where('label', $moduleLabel)->first();
        if ($module === null) {
            throw new ModelNotFoundException("The module << $moduleLabel >> doesn't exist!");
        }

        return $module;
    }
}
