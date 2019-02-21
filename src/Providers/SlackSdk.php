<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-19
 * Created Time: 15:12
 */

namespace STS\Slack\Providers;

use Illuminate\Support\ServiceProvider;
use STS\Slack\Contracts\SlashCommands\Dispatcher as DispatcherContract;
use STS\Slack\SlashCommands\Dispatcher;
use function base_path;

class SlackSdk extends ServiceProvider
{
    /**
     * Default path to laravel configuration file in the package
     *
     * @var string
     */
    protected $configPath = __DIR__ . '/../../config/slack.php';

    /**
     * Default path to Slack route file in the package
     *
     * @var string
     */
    protected $routePath = __DIR__ . '/../../routes/slack.php';

    public function register(): void
    {

        $this->handlePublishing();

        $this->app->singleton(Dispatcher::class, function ($app) {
            return Dispatcher::create(config('slack.slash_commands'));
        });

        $this->app->alias(
            Dispatcher::class,
            DispatcherContract::class
        );
        $this->app->alias(
            Dispatcher::class,
            'SlashCommandDispatcher'
        );
    }

    /**
     * Publish any artifacts to laravel user space
     */
    public function handlePublishing(): void
    {
        // helps deal with Lumen vs Laravel differences
        if (function_exists('config_path')) {
            $publishConfigPath = config_path('slack.php');
        } else {
            $publishConfigPath = base_path('config/slack.php');
        }
        $this->publishes([$this->configPath => $publishConfigPath], 'slack-configuration');
        $this->publishes([$this->routePath => base_path('routes/slack.php')], 'slack-routes');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            Dispatcher::class,
            DispatcherContract::class,
            'SlashCommandDispatcher',
        ];
    }

    public function boot(): void
    {
        $this->loadRoutesFrom($this->routePath);
    }
}
