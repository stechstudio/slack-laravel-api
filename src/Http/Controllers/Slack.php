<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-19
 * Created Time: 15:29
 */

namespace STS\Slack\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use STS\Slack\Models\SlashCommand;

class Slack extends Controller
{
    public function webhook(Request $request)
    {
        $slashCommand = SlashCommand::create($request->all());

    }
}
