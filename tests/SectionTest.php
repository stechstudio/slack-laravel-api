<?php

namespace STS\Slack\Tests;

use Orchestra\Testbench\TestCase;
use STS\Slack\Messaging\LayoutBlocks\Section;

class SectionTest extends TestCase
{
    public function testSectionCanAddText()
    {
        $s = Section::create("");
        $this->assertEquals(0, $s->getFields()->count());

        $s->text('Hello');
        $this->assertEquals(1, $s->getFields()->count());
        $this->assertEquals("Hello", $s->getFields()->last()->getText());
    }
}