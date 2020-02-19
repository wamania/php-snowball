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


$context = new Context('jaktbössa');
$snowball = new Snowball($context);
$stringCommand = new StringCommand($context);

try {
    $stringdef = (new Stringdef())
        ->add('a"', UTF8::hex_to_chr('U+00E4'))
        ->add('ao', UTF8::hex_to_chr('U+00E5'))
        ->add('o"', UTF8::hex_to_chr('U+00F6'))
    ;

    $grouping = (new Grouping($stringdef))
        ->add('v', 'aeiouy{a"}{ao}{o"}')
        ->add('s_ending', 'bcdfghjklmnoprtvy')
    ;

    $snowball
        ->define('mark_regions', function (Context $context) use ($snowball, $stringCommand, $grouping) {
            $p1 = $context->getLimit();
            $stringCommand->test(
                $snowball->chain(
                    $stringCommand->hop(3),
                    $stringCommand->setmark($p1)
                )
            );
            $snowball->chain(
                $stringCommand->goto(
                    $grouping->get('v')
                ),
                $stringCommand->gopast(
                    new Non($grouping->get('v'))
                ),
                $stringCommand->setmark($p1)
            );

            /*$stringCommand->test( hop 3 setmark x )
            goto v gopast non-v  setmark p1
            try ( $p1 < x  $p1 = x )*/
        })
        ->launch('mark_regions');

} catch (NotCallableException $e) {
};