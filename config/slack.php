<?php declare(strict_types=1);

return [
    /**
     * On each HTTP request that Slack sends, they add
     * an X-Slack-Signature HTTP header. The signature is
     * created by combining the signing secret with the body of the
     * request we're sending using a standard HMAC-SHA256 keyed hash.
     * https://api.slack.com/docs/verifying-requests-from-slack#about
     */
    'signing_secret' => env('SLACK_SIGNING_SECRET'),

    /**
     * Here you can define the slash commands and associated handler
     * for each command.
     *
     * You may register a class that implements a `handle(SlashCommand $slashCommand)` method
     * ```
     * '/foobar' => STS\Slack\Handlers\FooBar::class,
     * ```
     *
     * You may also use a closure or any other callable
     * ```
     * '/foo' => function (SlashCommand $slashCommand) {
     *      return do_something_with($slashCommand);
     * },
     * ```
     */
    'slash_commands' => [],
];
