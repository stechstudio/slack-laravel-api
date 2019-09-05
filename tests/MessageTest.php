<?php

namespace STS\Slack\Tests;

use Orchestra\Testbench\TestCase;
use STS\Slack\Messaging\LayoutBlocks\Image;
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


}