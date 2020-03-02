<?php

namespace Wamania\Snowball\Assigment;

use Wamania\Snowball\Exception\IntegerNotFoundException;
use Wamania\Snowball\Exception\InvalidOperandsCountException;
use Wamania\Snowball\Exception\OperatorNotFoundException;
use Wamania\Snowball\Type\IntegerBucket;

class Assigner
{
    private $availableOperators = [
        'PLUS_EQUAL' => '+=',
        'MINUS_EQUAL' => '-=',
        'TIMES_EQUAL' => '*=',
        'OVER_EQUAL' => '/=',
        'EQUAL' => '=',
    ];

    public function run(string $expression, IntegerBucket $integerBucket): void
    {
        $operator = null;
        foreach ($this->availableOperators as $availableOperator) {
            if (strpos($expression, $availableOperator) !== false) {
                $operator = $availableOperator;
                break;
            }
        }

        if (null === $operator) {
            throw new OperatorNotFoundException(sprintf('Operator not found in expression "%s"', $expression));
        }

        $operands = explode($operator, $expression);
        $operands = array_map(function($item) {
            return trim($item);
        }, $operands);

        if (count($operands) !== 2) {
            throw new InvalidOperandsCountException('Expecting 2 operands in expression "%", have %d', $expression, count($operands));
        }

        foreach ($operands as $operand) {
            if (!$integerBucket->has($operand)) {
                throw new IntegerNotFoundException(sprintf('Variable %s not found in the integer bucket.', $operand));
            }
        }

        switch ($operator) {
            case '=':
                $integerBucket->set(
                    $operands[0],
                    $integerBucket->get($operands[1])
                );
                break;

            case '+=':
                $integerBucket->set(
                    $operands[0],
                    $integerBucket->get($operands[0]) + $integerBucket->get($operands[1])
                );
                break;

            case '-=':
                $integerBucket->set(
                    $operands[0],
                    $integerBucket->get($operands[0]) - $integerBucket->get($operands[1])
                );
                break;

            case '*=':
                $integerBucket->set(
                    $operands[0],
                    $integerBucket->get($operands[0]) * $integerBucket->get($operands[1])
                );
                break;

            case '/=':
                $integerBucket->set(
                    $operands[0],
                    $integerBucket->get($operands[0]) / $integerBucket->get($operands[1])
                );
                break;
        }
    }
}
