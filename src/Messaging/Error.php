<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-02-21
 * Created Time: 18:00
 */

namespace STS\Slack\Messaging;

use Symfony\Component\HttpFoundation\Response;

class Error extends Message
{
    /** @var string */
    protected $text = "Sorry, that didn't work. Please try again.";

    public function getResponse(): Response
    {
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => $this->text,
        ])->setStatusCode(200);
    }
}
