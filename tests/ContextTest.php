<?php

namespace STS\Slack\Tests;

use Orchestra\Testbench\TestCase;
use STS\Slack\Messaging\LayoutBlocks\Context;

class ContextTest extends TestCase
{
    public function testContextCanAddText()
    {
        $c = Context::create();
        $this->assertEquals(0, $c->getElements()->count());

        $c->text("Hello");
        $this->assertEquals(1, $c->getElements()->count());
        $this->assertEquals("Hello", $c->getElements()->last()->getText());
    }
}