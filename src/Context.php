<?php

namespace Wamania\Snowball;

use voku\helper\UTF8;

class Context
{
    /** @var string */
    private $string;

    /** @var int */
    private $cursor;

    /** @var int */
    private $limit;

    public function __construct(string $string)
    {
        $this->string = $string;
        $this->cursor = 0;
        $this->limit = UTF8::strlen($string);
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function setString(string $string): Context
    {
        $this->string = $string;

        return $this;
    }

    public function getCursor(): int
    {
        return $this->cursor;
    }

    public function setCursor(int $cursor): Context
    {
        $this->cursor = $cursor;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): Context
    {
        $this->limit = $limit;

        return $this;
    }

    public function getSubstring(): string
    {

    }
}
