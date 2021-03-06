<?php

namespace STS\Slack\Tests;

use Orchestra\Testbench\TestCase;
use STS\Slack\Messaging\CompositionObjects\Text;
use STS\Slack\Messaging\LayoutBlocks\Context;
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

        $m->addImage('some-url', 'Alt Test');
        $this->assertTrue($m->hasBlocks());
        $this->assertInstanceOf(Image::class, $m->getBlocks()->last());
    }

    public function testMessageCanAddSection()
    {
        $m = Message::create('', '');
        $this->assertFalse($m->hasBlocks());

        // First with no callback
        $m->addSection("Hi there");
        $this->assertTrue($m->hasBlocks());
        $this->assertInstanceOf(Section::class, $m->getBlocks()->first());
        $this->assertEquals("Hi there", $m->getBlocks()->last()->getText()->getText());

        // Now with a callback
        $m->addSection("Hi there", function(Section $section) {
            $section->setText(Text::create("Hello"));
        });
        $this->assertEquals(2, $m->getBlocks()->count());
        $this->assertEquals("Hello", $m->getBlocks()->last()->getText()->getText());
    }

    public function testMessageCanAddDivider()
    {
        $m = Message::create('', '');
        $this->assertFalse($m->hasBlocks());

        $m->addDivider();
        $this->assertTrue($m->hasBlocks());
        $this->assertInstanceOf(Divider::class, $m->getBlocks()->first());
    }

    public function testMessageCanAddContext()
    {
        $m = Message::create('', '');
        $this->assertFalse($m->hasBlocks());

        $m->addContext(function(Context $context) {
            $context->push(Text::create("Hello"));
        });
        $this->assertEquals(1, $m->getBlocks()->count());
        $this->assertEquals(1, $m->getBlocks()->last()->getElements()->count());
    }

    public function testMessageIsTappable()
    {
        $m = Message::create('', 'Hey there');
        $this->assertEquals("Hey there", $m->getText());

        $m->tap(function(Message $message) {
            $message->setText("Hello");
        });

        $this->assertEquals("Hello", $m->getText());
    }

    public function testAddMultipleBlocksChained()
    {
        $result = Message::create('', '')
            ->addImage('image-url', 'Alt Text')
            ->addSection('Simple section')
            ->addSection('Complex section', function(Section $section) {
                $section->addText("This is a complex section");
            })
            ->addDivider()
            ->addContext(function(Context $context) {
                $context->addText('Contextual stuff');
            })
            ->tap(function(Message $m) {
                $m->setText("Changing the text");
            });

        $this->assertEquals(5, $result->getBlocks()->count());
    }
}