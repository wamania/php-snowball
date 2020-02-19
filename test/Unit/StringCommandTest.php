<?php

use \PHPUnit\Framework\TestCase;

class StringCommandTest extends TestCase
{
    public function setUp(): void
    {

    }

    public function testGoto()
    {
        $context = new \Wamania\Snowball\Context('aieopthr');
        $stringCommand = new \Wamania\Snowball\StringCommand($context);
        $stringCommand->goto(['eo']);

        $this->assertSame($context->getCursor(), 2);

        $context = new \Wamania\Snowball\Context('aieopthr');
        $stringCommand = new \Wamania\Snowball\StringCommand($context);
        $stringCommand->goto(['ie', 'p']);

        $this->assertSame($context->getCursor(), 4);
    }
}
