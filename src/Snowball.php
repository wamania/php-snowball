<?php

namespace Wamania\Snowball;

use Wamania\Snowball\Exception\NotCallableException;
use Wamania\Snowball\Exception\UnknownVariableException;

class Snowball
{
    private $context;

    private $definitions;

    public function __construct(Context $context)
    {
        $this->context = $context;
        $this->definitions = [];
    }

	public function define(string $key, $value): self
    {
        $this->definitions[$key] = $value;

        return $this;
    }

    public function getVar($key)
    {
        if (!isset($this->definitions[$key])) {
            throw new UnknownVariableException(sprintf('Unknown variable %s', $key));
        }

        return $this->definitions[$key];
    }

    public function launch(string $key): self
    {
        if ((!isset($this->definitions[$key])) || !is_callable($this->definitions[$key])) {
            throw new NotCallableException(sprintf('Unknown command %s', $key));
        }

        $this->definitions[$key]($this->context);

        return $this;
    }

    public function chain(...$commands): bool
    {
        foreach ($commands as $command) {
            if (is_callable($command)) {
                if (! $command()) {
                    return false;
                }
            }
        }

        return true;
    }
}
