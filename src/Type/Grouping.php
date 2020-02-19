<?php

namespace Wamania\Snowball\Type;

use Wamania\Snowball\Exception\UnknownGroupingException;

class Grouping
{
    /** @var array */
    private $definitions;

    /** @var Stringdef */
    private $stringdef;

    public function __construct(Stringdef $stringdef)
    {
        $this->definitions = [];
        $this->stringdef = $stringdef;
    }

    public function add(string $key, string $value): self
    {
        $stringdefs = array_flip(array_map(function($v) {
            return sprintf('{%s}', $v);
        }, array_flip($this->stringdef->all())));

        $this->definitions[$key] = str_split(strtr($value, $stringdefs));

        return $this;
    }

    public function get(string $key): array
    {
        if (!isset($this->definitions[$key])) {
            throw new UnknownGroupingException(sprintf('Unknown grouping %s', $key));
        }

        return $this->definitions[$key];
    }
}
