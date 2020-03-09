<?php

namespace Wamania\Snowball\Command;

class AmongLine
{
    /** @var array */
    private $suffixes;

    /** @var callable */
    private $command;

    public function __construct(array $suffixes, callable $command)
    {
        $this->suffixes = $suffixes;
        $this->command = $command;
    }

    public function apply()
    {

    }

    public function getSuffixes()
    {
        return $this->suffixes;
    }
}
