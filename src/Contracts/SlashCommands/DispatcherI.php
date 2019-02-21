<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 14:49
 */

namespace STS\Slack\Contracts\SlashCommands;

use STS\Slack\Models\SlashCommand;

interface DispatcherI
{
    /**
     * Takes in a configuration and registers all the command handlers.
     */
    public function registerConfiguredHandlers(array $config): DispatcherI;

    /**
     * Register a command handler with the dispatcher.
     */
    public function handles(string $command, callable $handler): DispatcherI;

    /**
     * Determine if a given command has a handler.
     */
    public function hasHandler(string $command): bool;

    /**
     * Dispatch a command and call the handler.
     *
     * @return mixed
     */
    public function dispatch(SlashCommand $slashCommand);

    /**
     * Remove a command from the dispatcher.
     */
    public function forget(string $command): DispatcherI;


}
