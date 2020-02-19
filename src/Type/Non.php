<?php

namespace Wamania\Snowball\Type;

class Non
{
    /** @var array  */
    private $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function get(): array
    {
        return $this->value;
    }
}
