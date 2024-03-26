<?php

namespace App\Core;

class Bag
{
    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function get(string $key, $defaultValue = null)
    {
        if (!isset($this->params[$key]) && !$this->defaultValueIsNotNull($defaultValue)) {
            return null;
        }

        if (!isset($this->params[$key]) && $this->defaultValueIsNotNull($defaultValue)) {
            return $defaultValue;
        }

        return $this->params[$key];
    }

    public function all(): array
    {
        return $this->params;
    }

    private function defaultValueIsNotNull($defaultValue): bool
    {
        return $defaultValue !== null;
    }
}
