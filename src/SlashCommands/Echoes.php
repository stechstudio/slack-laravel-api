<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 14:48
 */

namespace STS\Slack\SlashCommands;

use Illuminate\Support\Facades\Log;
use STS\Slack\Contracts\Messaging\Message as SlackMessage;
use STS\Slack\Contracts\SlashCommands\ControllerI;
use STS\Slack\Messaging\BlockElements\Image;
use STS\Slack\Messaging\CompositionObjects\Text;
use STS\Slack\Messaging\LayoutBlocks\Context;
use STS\Slack\Messaging\LayoutBlocks\Divider;
use STS\Slack\Messaging\LayoutBlocks\Section;
use STS\Slack\Messaging\Message;
use STS\Slack\Models\SlashCommand;
use function json_encode;

class Echoes implements ControllerI
{

    public function handle(SlashCommand $slashCommand): SlackMessage
    {
        $message = Message::create($slashCommand->getChannelId(), '*Echo:*  ' . $slashCommand->getText())
            ->push(
                \STS\Slack\Messaging\LayoutBlocks\Image::create(
                    'https://cdn.cp.adobe.io/content/2/dcx/9ed8e319-b714-4c8d-b9d5-7a6d419e50b3/rendition/preview.jpg/version/0/format/jpg/dimension/width/size/1200',
                    'Echo Hacker'
                )
            )
            ->push(
                Section::create('*Slack Parameters* ')
                    ->push(Text::create("*Team ID*: {$slashCommand->getTeamId()}"))
                    ->push(Text::create("*Team Domain*: {$slashCommand->getTeamDomain()}"))
                    ->push(Text::create("*Channel ID*: {$slashCommand->getChannelId()}"))
                    ->push(Text::create("*Channel Name*: {$slashCommand->getChannelName()}"))
                    ->push(Text::create("*User ID*: {$slashCommand->getUserId()}"))
                    ->push(Text::create("*User Name*: {$slashCommand->getUserName()}"))
                    ->push(Text::create("*Command*: {$slashCommand->getCommand()}"))
            )
            ->push(
                Section::create("*Your Text*\n{$slashCommand->getText()}")
            )
            ->push(Divider::create())
            ->push(
                Context::create()
                    ->push(Image::create(
                        'https://avatars.slack-edge.com/2019-02-19/556373803382_e2c54afedc2a4fb73ccd_512.png',
                        'The Commander Logo'
                    ))
                    ->push(Text::create('slack-laravel-api echo handler'))
            );
        Log::warning(json_encode($message->toSlackObjectArray()));
        return $message;
    }
}
