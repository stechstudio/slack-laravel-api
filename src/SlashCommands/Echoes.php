<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 14:48
 */

namespace STS\Slack\SlashCommands;

use STS\Slack\Contracts\Messaging\Message as SlackMessage;
use STS\Slack\Messaging\Message;
use STS\Slack\Models\SlashCommand;

class Echoes
{

    public function handle(SlashCommand $slashCommand): SlackMessage
    {
        return Message::create('It works');
    }
}
