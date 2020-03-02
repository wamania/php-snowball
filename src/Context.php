<?php

namespace Wamania\Snowball;

use voku\helper\UTF8;
use Wamania\Snowball\Exception\ContextException;

class Context
{
    /** @var string */
    private $string;

    /** @var int */
    private $cursor;

    /** @var int */
    private $limit;

    /** @var string */
    private $mode;

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
        return UTF8::substr($this->string, $this->cursor, ($this->limit - $this->cursor));
    }

    /**
     * @throws ContextException
     */
    public function setMode(string $mode): Context
    {
        if (($mode !== 'forward') && ($mode !== 'backward')) {
            throw new ContextException(sprintf('Unknown mode %', $mode));
        }

        $this->mode = $mode;

        return $this;
    }



    /*public function getMode()
    {

    }*/
}
