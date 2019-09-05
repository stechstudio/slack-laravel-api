<?php declare(strict_types=1);

namespace STS\Slack\Contracts\SlashCommands;

use STS\Slack\Contracts\Messaging\Message as SlackMessage;
use STS\Slack\Models\SlashCommand;

interface ControllerI
{
    /**
     * Handles a SlashCommand
     */
    public function handle(SlashCommand $slashCommand): SlackMessage;
}