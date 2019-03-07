<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-03-07
 * Created Time: 16:05
 */

namespace STS\Slack\Facades;

use Illuminate\Support\Facades\Facade;
use STS\Slack\Contracts\SlashCommands\DispatcherI;
use STS\Slack\Models\SlashCommand;

/**
 * @method static mixed dispatch(SlashCommand $slashCommand)
 * @method static DispatcherI forget(string $command)
 * @method static DispatcherI registerConfiguredHandlers(array $config)
 * @method static DispatcherI handles(string $command, callable $handler)
 * @method static bool hasHandler(string $command)
 */
class SlackRoute extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'slack.slash.commander';
    }
}
