<?php

namespace App\Repositories;


use App\Models\Module;
use App\Models\ModuleParameter;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\AssignOp\Mod;

class ModuleRepository
{
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

        /** @var ModuleParameter $moduleParameter */
        $moduleParameter = ModuleParameter::query()
            ->whereHas('module', function (Builder $query) use ($moduleLabel) {
                $query->where('label', $moduleLabel);
            })
            ->where('label', $parameterLabel)
            ->first();
        if ($moduleParameter) {
            $this->transformValueToGet($moduleParameter);
        }

        $this->cache[$moduleLabel][$parameterLabel] = $moduleParameter;

        return $moduleParameter;
    }

    private function transformValueToGet (ModuleParameter $moduleParameter)
    {
        $value = $moduleParameter->value;

        if (Str::startsWith($value, ['{', '['])) {
            $newValue = json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $newValue;
            }
        }

        $moduleParameter->value = $value;
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

        $this->cache[$moduleLabel][$parameterLabel] = $moduleParameter;

        return $moduleParameter;
    }

    public function getModule (string $moduleLabel): Module
    {
        if (!$module = Module::where('label', $moduleLabel)->first()) {
            throw new Exception("The module << $moduleLabel >> doesn't exist!");
        }

        return $module;
    }

    public function isActive (string $moduleLabel): bool
    {
        $this->getModule($moduleLabel)->is_active;
    }

    public function destroyModule (string $label): void
    {
        $this->getModule($label)->delete();
    }

    public function activate (string $moduleLabel)
    {
        $this->updateModule($moduleLabel, ['is_active' => true]);
    }

    public function updateModule (string $moduleLabel, array $data)
    {
        $this->getModule($moduleLabel)->update($data);
    }

    public function deactivate (string $moduleLabel)
    {
        $this->updateModule($moduleLabel, ['is_active' => false]);
    }

    private function transformValueToSave (ModuleParameter $moduleParameter)
    {
        $value = $moduleParameter->value;

        if (is_array($value)) {
            $value = json_encode($value);
        }

        $moduleParameter->value = $value;
    }
}
