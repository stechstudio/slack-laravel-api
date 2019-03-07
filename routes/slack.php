<?php declare(strict_types=1);

use STS\Slack\Facades\SlackRoute;
use STS\Slack\SlashCommands\Echoes;

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
