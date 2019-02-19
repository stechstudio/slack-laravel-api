<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-19
 * Created Time: 15:12
 */

namespace STS\Slack\Providers;

use Illuminate\Support\ServiceProvider;
use STS\Slack\Http\Middleware\Request;

class SlackSdk extends ServiceProvider
{
    /**
     * Default path to laravel configuration file in the package
     *
     * @var string
     */
    protected $configPath = __DIR__ . '/../../config/slack.php';

    public function register(): void
    {
        $this->handlePublishing();
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
    }

    public function boot(): void
    {
        $this->app['router']
            ->middlewareGroup(
                'slack',
                [
                    'throttle:60,1',
                    'bindings',
                    Request::class,
                ]
            )
            ->middleware('slack')
            ->namespace('STS\Slack\Controllers')
            ->match(['get', 'post'], '/slack/api', 'Slack@webhook');
    }
}
