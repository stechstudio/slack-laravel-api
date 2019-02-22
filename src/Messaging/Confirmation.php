<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-02-21
 * Created Time: 16:16
 */

namespace STS\Slack\Messaging;

use STS\Slack\Contracts\Messaging\Message;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Confirmation
 * Slash Command confirmation must be received by Slack within 3000 milliseconds of the original request being sent,
 * otherwise a Timeout was reached will be displayed to the user. If you couldn't verify the request payload,
 * your app should return an error instead and ignore the request.
 */
class Confirmation implements Message
{
    public static function create(): self
    {
        return new static;
    }

    public function __toString(): string
    {
        return $this->getResponse()->__toString();
    }

    public function getResponse(): Response
    {
        return new Response(null, 200);
    }
}
