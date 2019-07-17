<?php

namespace App\Repositories;


use App\Models\Module;
use App\Models\ModuleParameter;
use Exception;
use Illuminate\Support\Str;

class ModuleRepository
{
    public function createModule (string $moduleLabel, bool $is_active = false, string $description = null): Module
    {
        $module = new Module([
            'label'       => $moduleLabel,
            'is_active'   => $is_active,
            'description' => $description
        ]);

        $module->save();

        return $module;
    }

    public function getParameter (string $moduleLabel, string $parameterLabel): ?ModuleParameter
    {
        $module = $this->getModule($moduleLabel);

        /** @var ModuleParameter $moduleParameter */
        $moduleParameter = $module->parameters()->where('label', $parameterLabel)->first();
        if ($moduleParameter) {
            $this->transformValue($moduleParameter);
        }

        return $moduleParameter;
    }

    public function getModule (string $moduleLabel): Module
    {
        if (!$module = Module::where('label', $moduleLabel)->first()) {
            throw new Exception("The module << $moduleLabel >> doesn't exist!");
        }

        return $module;
    }

    private function transformValue (ModuleParameter $moduleParameter)
    {
        $value = $moduleParameter->value;

        if (Str::startsWith($value, ['{', '['])) {
            $newValue = json_decode($value, true);
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

        return $this->getModule($moduleLabel)->parameters()->create([
            'label'       => $parameterLabel,
            'value'       => $value,
            'description' => $description
        ]);
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
}
