<?php

namespace Wamania\Snowball;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Wamania\Snowball\Assigment\Assigner;
use Wamania\Snowball\Exception\IntegerNotFoundException;
use Wamania\Snowball\ExpressionAssignment\Lexer;
use Wamania\Snowball\Type\IntegerBucket;

class IntegerCommand
{
    /** @var Context */
    private $context;

    /** @var ExpressionLanguage */
    private $expressionLanguage;

    /** @var IntegerBucket */
    private $integerBucket;

    public function __construct(Context $context, IntegerBucket $integerBucket)
    {
        $this->context = $context;
        $this->integerBucket = $integerBucket;
        $this->expressionLanguage = new ExpressionLanguage();
    }

    /**
     * @throws Exception\IntegerNotFoundException
     * @throws Exception\InvalidOperandsCountException
     * @throws Exception\OperatorNotFoundException
     */
    public function assignment(string $expression): bool
    {
        (new Assigner())
            ->run($expression, $this->integerBucket);

        return true;
    }

    public function comparison(string $expression): bool
    {
        return $this->expressionLanguage->evaluate($expression, $this->integerBucket->all());
    }

    public function setmark(string $key): bool
    {
        if (!$this->integerBucket->has($key)) {
            throw new IntegerNotFoundException(sprintf('Variable %s not found in the integer bucket.', $key));
        }

        $this->integerBucket->set($key, $this->context->getCursor());

        return true;
    }

    public function tomark(string $key): bool
    {
        if (!$this->integerBucket->has($key)) {
            throw new IntegerNotFoundException(sprintf('Variable %s not found in the integer bucket.', $key));
        }

        $value = $this->integerBucket->get($key);

        if ($value > $this->context->getCursor()) {
            return false;
        }

        if ($this->context->getLimit() < $value) {
            return false;
        }

        $this->context->setCursor($value);

        return true;
    }
}
