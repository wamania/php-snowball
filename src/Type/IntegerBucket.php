<?php

namespace Wamania\Snowball\Type;

class IntegerBucket
{
    /** @var array */
    private $definitions;

    public function __construct()
    {
        $this->definitions = [];
    }

    public function set(string $key, int $value = null): self
    {
        $this->definitions[$key] = $value;

        return $this;
    }

    public function get(string $key): ?int
    {
        return (array_key_exists($key, $this->definitions) ? $this->definitions[$key] : null);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->definitions);
    }

    public function all(): array
    {
        return $this->definitions;
    }
}
