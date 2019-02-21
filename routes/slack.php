<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-02-21
 * Created Time: 10:41
 */

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
