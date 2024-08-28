<?php
namespace Storhn\Reporter\Helpers;

use Illuminate\Support\Facades\Log;

Trait Validator {

    protected $allowedKeys = [
        'client' => 'string',
        'project' => 'string',
        'level' => 'int',
        'message' => 'string',
        'file' => 'string',
        'line' => 'int',
    ];

    protected $mandatoryKeys = ['project', 'level', 'message', 'timestamp'];
    
    protected function validateData(array $data): bool
    {
        foreach (array_keys($data) as $key) {
            if (!array_key_exists($key, $this->allowedKeys)) {
                // Au lieu de Log, ici, on envoie quand même l'erreur. Mais on la tweak pour montrer qu'elle est flinguée.
                Log::error('Invalid data key: ' . $key);
                return false;
            }
            if (!$this->validateType($value, $this->allowedKeys[$key])) {
                // Comme au dessus ?
                Log::error("Invalid data type for key '{$key}': Expected {$this->allowedKeys[$key]}");
                return false;
            }
        }

        foreach ($this->mandatoryKeys as $key) {
            if (!array_key_exists($key, $data)) {
                // Pareil qu'au dessus.
                Log::error('Missing mandatory data key: ' . $key);
                return false;
            }
        }

        return true;
    }

    protected function validateType($value, string $expectedType): bool
    {
        switch ($expectedType) {
            case 'string':
                return is_string($value);
            case 'int':
                return is_int($value);
            default:
                return false;
        }
    }
}