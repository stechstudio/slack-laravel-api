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

    public function testContextCanAddImage()
    {
        $c = Context::create();
        $this->assertEquals(0, $c->getElements()->count());

        $c->image("some-url", 'Alt Text');
        $this->assertEquals(1, $c->getElements()->count());
        $this->assertEquals("some-url", $c->getElements()->last()->getImageUrl());
    }
}