<?php

namespace Wamania\Snowball;

use voku\helper\UTF8;
use Wamania\Snowball\Type\Non;

class StringCommand
{
    /** @var Context */
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function _(string $string):bool
    {
        $substring = $this->context->getSubstring();

        if (UTF8::strpos($substring, $string) === 0) {

            return true;
        }

        return false;
    }

    public function test(callable $command)
    {
        return $command();
    }

    public function try(callable $command)
    {
        $cursor = $this->context->getCursor();

        if (!$command()) {
            // if f signal, restore cursor
            $this->context->setCursor($cursor);
        }

        return true;
    }

    public function goto($variable): bool
    {
        if ($variable instanceof Non) {
            return $this->gotoNon($variable);
        }

        if (is_string($variable)) {
            $variable = [$variable];
        }

        $found = false;

        for ($i=$this->context->getCursor(); $i<$this->context->getLimit(); $i++) {
            $substring = UTF8::substr($this->context->getString(), $i);
            foreach ($variable as $item) {
                if (UTF8::strpos($substring, $item) === 0) {
                    $this->context->setCursor($i);
                    $found = true;
                }
            }
        }

        return $found;
    }

    private function gotoNon(Non $variable): bool
    {
        $variables = $variable->get();

        if (is_string($variables)) {
            $variables = [$variables];
        }

        for ($i=$this->context->getCursor(); $i<$this->context->getLimit(); $i++) {
            $substring = UTF8::substr($this->context->getString(), $i);
            $found = false;
            foreach ($variables as $item) {
                if (UTF8::strpos($substring, $item) === 0) {
                    $found = true;
                }
            }

            if (!$found) {
                $this->context->setCursor($i);
            }
        }

        return !$found;
    }

    public function gopast($variable): bool
    {
        if ($variable instanceof Non) {
            return $this->gopastNon($variable);
        }

        if (is_string($variable)) {
            $variable = [$variable];
        }

        for ($i=$this->context->getCursor(); $i<$this->context->getLimit(); $i++) {
            $substring = UTF8::substr($this->context->getString(), $i);
            $found = false;
            foreach ($variable as $item) {
                if (UTF8::strpos($substring, $item) === 0) {
                    $this->context->setCursor($i + UTF8::strlen($item));
                    $found = true;
                }
            }
        }

        return $found;
    }

    private function gopastNon(Non $variable): bool
    {
        $variables = $variable->get();

        if (is_string($variables)) {
            $variables = [$variables];
        }

        for ($i=$this->context->getCursor(); $i<$this->context->getLimit(); $i++) {
            $substring = UTF8::substr($this->context->getString(), $i);
            $found = false;
            foreach ($variables as $item) {
                if (UTF8::strpos($substring, $item) === 0) {
                    $found = true;
                    $length = UTF8::strlen($item);
                }
            }

            if (!$found) {
                $this->context->setCursor($i+1);
            }
        }

        return !$found;
    }

    public function hop(int $number): bool
    {
        if ($number < 0) {
            return false;
        }

        if (($this->context->getCursor() + $number) > $this->context->getLimit()) {
            return false;
        }

        $this->context->setCursor($this->context->getCursor() + $number);

        return true;
    }

    public function setlimit(callable $c1, callable $c2): bool
    {
        $cursor = $this->context->getCursor();
        $limit = $this->context->getLimit();

        if (!$c1()) {
            return false;
        }

        $this->context->setLimit($this->context->getCursor());
        $this->context->setCursor($cursor);

        $return = $c2();

        $this->context->setLimit($limit);

        return $return;
    }

    /**
     * [substring] similar to $context->getSubtring
     */
    public function brackets()
    {
        return $this->context->getSubstring();
    }

    public function among(callable $substring, callable $lines): bool
    {
        $substring = $substring();
    }

    /*public function try()
    {
        return true;
    }*/


}