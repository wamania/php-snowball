<?php

require 'vendor/autoload.php';

use voku\helper\UTF8;
use Wamania\Snowball\Snowball;
use Wamania\Snowball\Context;
use Wamania\Snowball\StringCommand;
use Wamania\Snowball\Exception\NotCallableException;
use Wamania\Snowball\Type\Stringdef;
use Wamania\Snowball\Type\Grouping;
use Wamania\Snowball\Type\Non;
use Wamania\Snowball\IntegerCommand;
use Wamania\Snowball\Type\IntegerBucket;

$context = new Context('jaktbössa');

try {
    $integerBucket = (new IntegerBucket())
        ->set('p1', null)
        ->set('x', null)
    ;

    $stringdef = (new Stringdef())
        ->set('a"', UTF8::hex_to_chr('U+00E4'))
        ->set('ao', UTF8::hex_to_chr('U+00E5'))
        ->set('o"', UTF8::hex_to_chr('U+00F6'))
    ;

    $grouping = (new Grouping($stringdef))
        ->set('v', 'aeiouy{a"}{ao}{o"}')
        ->set('s_ending', 'bcdfghjklmnoprtvy')
    ;

    $snowball = new Snowball($context);
    $stringCommand = new StringCommand($context);
    $integerCommand = new IntegerCommand($context, $integerBucket);

    $snowball
        ->define('mark_regions', function (Context $context) use ($snowball, $stringCommand, $integerCommand, $grouping) {
            // @todo $p1 = $context->getLimit();
            $stringCommand->test(function() use ($snowball, $stringCommand, $integerCommand) {
                $snowball->chain(
                    $stringCommand->hop(3),
                    $integerCommand->setmark('x')
                );
            });
            $snowball->chain(
                $stringCommand->goto(
                    $grouping->get('v')
                ),
                $stringCommand->gopast(
                    new Non($grouping->get('v'))
                ),
                $integerCommand->setmark('p1')
            );

            $stringCommand->try(function() use ($snowball, $integerCommand) {
                $snowball->chain(
                    $integerCommand->comparison('p1 < x'),
                    $integerCommand->assignment('p1 = x')
                );
            });
        })
        ->define('main_suffix', function() use ($snowball, $stringCommand, $integerCommand) {
            $stringCommand->setlimit(
                function() use ($integerCommand) {
                    return $integerCommand->tomark('p1');
                }, function() use ($stringCommand) {
                    return $stringCommand->among(
                        function () use ($stringCommand) {
                            return $stringCommand->brackets();
                        }, function () {
                            return ;
                        }
                    );
                }
            );
        })
        ->launch('mark_regions');

} catch (NotCallableException $e) {
};

dump($integerBucket);