<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 14:48
 */

namespace STS\Slack\SlashCommands;

use STS\Slack\Contracts\SlashCommands\DispatcherI;
use STS\Slack\Exceptions\InvalidHandlerConfiguration;
use STS\Slack\Models\SlashCommand;
use function class_exists;
use function is_callable;

class Dispatcher implements DispatcherI
{
    /**
     * The registered command handlers.
     *
     * @var array
     */
    protected $handlers = [];

    public static function create(array $config = []): DispatcherI
    {
        $dispatcher = new static;
        $dispatcher->registerConfiguredHandlers($config);
        return $dispatcher;
    }

    /**
     * Takes in a configuration and registers all the command handlers.
     */
    public function registerConfiguredHandlers(array $config): DispatcherI
    {
        foreach ($config as $command => $handler) {
            if (is_callable($handler)) {
                $this->handles($command, $handler);
                continue;
            }
            if (class_exists($handler)) {
                $this->handles($command, [$handler, 'handle']);
                continue;
            }
            throw new InvalidHandlerConfiguration(sprintf('[%s] is an invalid handler.', $handler));
        }
        return $this;
    }

    /**
     * Register a command handler with the dispatcher.
     */
    public function handles(string $command, callable $handler): DispatcherI
    {
        $this->handlers[$command] = $handler;
        return $this;
    }

    /**
     * Determine if a given command has a handler.
     */
    public function hasHandler(string $command): bool
    {
        return isset($this->handlers[$command]);
    }

    /**
     * Dispatch a command and call the handler.
     *
     * @return mixed
     */
    public function dispatch(SlashCommand $slashCommand)
    {
        dd(call_user_func($this->handlers[$slashCommand->getCommand()], $slashCommand));
        return call_user_func($this->handlers[$slashCommand->getCommand()], $slashCommand);
    }

    /**
     * Forget a registered command
     */
    public function forget(string $command): DispatcherI
    {
        unset($this->handlers[$command]);
        return $this;
    }


}
