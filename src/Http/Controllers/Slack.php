<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-19
 * Created Time: 15:29
 */

namespace STS\Slack\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use STS\Slack\Exceptions\HandlerUndefined;
use STS\Slack\Models\SlashCommand;

class Slack extends Controller
{
    /**
     * @return mixed
     */
    public function webhook(Request $request)
    {
        $slashCommand = SlashCommand::create($request->all());
        try {
            return $slashCommand->dispatch();
        } catch (HandlerUndefined $exception) {
            return response()->json([
                'error' => 'No Handler Defined.',
            ])->setStatusCode(400);
        }
    }
}
