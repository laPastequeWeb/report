<?php
namespace Storhn\Reporter\Helpers;

Trait Validator {

    protected $allowedKeys = ['client' => 'string', 'client_url' => 'string', 'project' => 'string', 'level' => 'int', 'message' => 'string', 'file' => 'string', 'line' => 'int', 'date' => 'string'];
    protected $mandatoryKeys = ['client', 'client_url', 'project', 'level', 'message', 'date'];
    
    protected function validateData(array $data): bool
    {
        foreach (array_keys($data) as $key) {
            if (!array_key_exists($key, $this->allowedKeys)) {
                return false;
            }
        }
        foreach ($this->mandatoryKeys as $key) {
            if (!array_key_exists($key, $data)) {
                return false;
            }
        }
        return true;
    }
}