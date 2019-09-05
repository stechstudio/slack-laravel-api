<?php

namespace STS\Slack\Tests;

use Orchestra\Testbench\TestCase;
use STS\Slack\Messaging\Message;
use STS\Slack\Models\SlashCommand;

class CommandTest extends TestCase
{
    public function testCommandCanCreateMessage()
    {
        $command = new SlashCommand([
            'channel_id' => '12345',
            'text' => "Hello"
        ]);

        $message = $command->createMessage();

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals(12345, $message->getChannel());
        $this->assertEquals("Hello", $message->getText());

        $message = $command->createMessage("Yo");

        $this->assertEquals("Yo", $message->getText());
    }
}