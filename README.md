# Slack Laravel API
Easily handle Slack web hook requests via Laravel style routing with Middleware authentication. If you have ever implemented a webhook for Slack, your going to want to try this.

## Versions and compatibility
* **PHP Version:** *>=7.2*
* **Laravel**: *^5.7|^6.0*

## Installation
#### Composer
```bash
composer require stechstudio/slack-laravel-api
```
## Configuration

#### .env
On each HTTP request that Slack sends, they [add an X-Slack-Signature HTTP header](https://api.slack.com/docs/verifying-requests-from-slack#about) . The signature is created by combining the signing secret with the body of the request we're sending using a standard HMAC-SHA256 keyed hash.

It is this signing secret that is used by the middleware to authenticate the request.
```ini
SLACK_SIGNING_SECRET="slacksecretestringhere"
```

#### Publish Slack Routes
```bash
artisan vendor:publish --provider=slack-routes
```

## Usage
After publishing the Slack Routes you can look in `base('routes/slack.php)` to find the API route and some working examples of Slack routes.

#### Web Route
```php 
/*
|--------------------------------------------------------------------------
| Slack Webhook Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Slack routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "slack" middleware group. Enjoy building your API!
|
*/

Route::middleware('slack')->match(['get', 'post'], '/slack/api', 'STS\Slack\Http\Controllers\Slack@webhook');
````

By default, your application will have **GET|POST|HEAD** methods on the `slack/api` URI that will pass everything to `STS\Slack\Http\Controllers\Slack@webhook` via the **slack** middleware.

You may modify the the URI or even add addition routes for other endpoints.
#### Slack Command Routes
```php
use STS\Slack\Facades\SlackRoute;
use STS\Slack\SlashCommands\Echoes;

/*
|--------------------------------------------------------------------------
| Slack Command Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Slack commands for your application.
|
*/
SlackRoute::handles('/hello', function (SlashCommand $slashCommand) {
    return 'Hello World';
});

SlackRoute::handles('/echo', Echoes::class);
```

These are working examples, and if you setup both Slack slash commands to use the configured URI, simply typing `/hello` or `/echo yoodle` will trigger the appropriate response.

THe `SlackRoute::handles()` expects you to provide a command that matches the slash command in Slack, along with a Callable to handle the command.

This is best demonstrated in the Echo command sample.

```php
<?php declare(strict_types=1);

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
```

First note that your Command must implement `STS\Slack\Contracts\SlashCommands\ControllerI` in order to be valid. This ensures that we have a `handle()` that takes a **SlashCommand** and returns a **SlackMessage.**

As you can see, the **SlashCommand** provides access to everything that Slack sends you. The two most commonly used will be:

* `$slashCommand->getCommand()`
* `$slashCommand->getText()`

Assuming the user typed `\echo yoodle ay e who` into Slack to trigger this. Then `getCommand()` would return `\echo` and `getText()` returns `yoodle ay e who`. We often then parse the text for sub-commands.

The `SlackMessage` class is a fairly powerful wrapper the [Building Block message layout format specified by the Slack API](https://api.slack.com/messaging/composing/layouts). Anything you can do with the blocks, you can do with this class.

## Conclusion
That's it, handle your command and return a slack formatted message with Laravel Middleware authenticating each slack call for you and some handy Classes to make handling these things easier.