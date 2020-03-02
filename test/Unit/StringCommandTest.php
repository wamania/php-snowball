<?php

namespace Wamania\Snowball\Tests;

use PHPUnit\Framework\TestCase;
use Wamania\Snowball\StringCommand;
use Wamania\Snowball\Type\Grouping;
use Wamania\Snowball\Type\Non;
use Wamania\Snowball\Context;
use Wamania\Snowball\Type\Stringdef;

class StringCommandTest extends TestCase
{
    public function setUp(): void
    {

    }

    public function testGoto()
    {
        $context = new Context('aieopthr');
        $stringCommand = new StringCommand($context);
        $stringCommand->goto(['eo']);

        $this->assertSame($context->getCursor(), 2);

        $context = new Context('aieopthr');
        $stringCommand = new StringCommand($context);
        $stringCommand->goto(['ie', 'p']);

        $this->assertSame($context->getCursor(), 4);
    }

    public function testGopast()
    {
        $context = new Context('aieopthr');
        $stringCommand = new StringCommand($context);
        $stringCommand->gopast(['eo']);

        $this->assertSame($context->getCursor(), 4);

        $context = new Context('aieopthr');
        $stringCommand = new StringCommand($context);
        $stringCommand->gopast(['ie', 'p']);

        $this->assertSame($context->getCursor(), 5);
    }

    public function testNonGoto()
    {
        $context = new Context('aieopthr');
        $stringCommand = new StringCommand($context);
        $stringCommand->goto(
            new Non(
                (new Grouping(
                    new Stringdef()
                ))
                    ->add('v', 'aieoptr')
                    ->get('v')
            )
        );

        $this->assertSame($context->getCursor(), 6);
    }

    public function testNonGopast()
    {
        $context = new Context('aieopthr');
        $stringCommand = new StringCommand($context);
        $stringCommand->gopast(
            new Non(
                ['aie', 'hr', 'r']
            )
        );

        $this->assertSame($context->getCursor(), 6);
    }
}
