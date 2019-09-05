<?php

namespace STS\Slack\Tests;

use Orchestra\Testbench\TestCase;
use STS\Slack\Messaging\CompositionObjects\Text;
use STS\Slack\Messaging\LayoutBlocks\Divider;
use STS\Slack\Messaging\LayoutBlocks\Image;
use STS\Slack\Messaging\LayoutBlocks\Section;
use STS\Slack\Messaging\Message;

class MessageTest extends TestCase
{
    public function testMessageCanAddImage()
    {
        $m = Message::create('', '');
        $this->assertFalse($m->hasBlocks());

        $m->image('some-url', 'Alt Test');
        $this->assertTrue($m->hasBlocks());
        $this->assertInstanceOf(Image::class, $m->getBlocks()->first());
    }

    public function testMessageCanAddSection()
    {
        $m = Message::create('', '');
        $this->assertFalse($m->hasBlocks());

        // First with no callback
        $m->section("Hi there");
        $this->assertTrue($m->hasBlocks());
        $this->assertInstanceOf(Section::class, $m->getBlocks()->first());
        $this->assertEquals("Hi there", $m->getBlocks()->first()->getText()->getText());

        // Now with a callback
        $m->section("Hi there", function(Section $section) {
            $section->setText(Text::create("Hello"));
        });
        $this->assertEquals(2, $m->getBlocks()->count());
        $this->assertEquals("Hello", $m->getBlocks()->last()->getText()->getText());
    }

    public function testMessageCanAddDivider()
    {
        $m = Message::create('', '');
        $this->assertFalse($m->hasBlocks());

        $m->divider();
        $this->assertTrue($m->hasBlocks());
        $this->assertInstanceOf(Divider::class, $m->getBlocks()->first());
    }
}