<?php

namespace Wamania\Snowball\Type;

class Stringdef
{
    /** @var array */
    private $definitions;

    public function __construct()
    {
        $this->definitions = [];
    }

    public function set(string $key, string $value): self
    {
        $this->definitions[$key] = $value;

        return $this;
    }

    public function all(): array
    {
        return $this->definitions;
    }
}
