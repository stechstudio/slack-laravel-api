<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 14:49
 */

namespace STS\Slack\Contracts\SlashCommands;

use STS\Slack\Models\SlashCommand;

interface Dispatcher
{
    /**
     * Takes in a configuration and registers all the command handlers.
     */
    public function registerConfiguredHandlers(array $config): Dispatcher;

    /**
     * Register a command handler with the dispatcher.
     */
    public function handles(string $command, callable $handler): Dispatcher;

    /**
     * Determine if a given command has a handler.
     */
    public function hasHandler(string $command): bool;

    /**
     * Dispatch a command and call the handler.
     *
     * @return mixed
     */
    public function dispatch(string $command, SlashCommand $slashCommand);

    /**
     * Remove a command from the dispatcher.
     */
    public function forget(string $command): Dispatcher;


}
