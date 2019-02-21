<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-02-21
 * Created Time: 16:04
 */

namespace STS\Slack\Messaging;

use Illuminate\Http\JsonResponse;
use STS\Slack\Contracts\Messaging\Message as SlackMessage;

class Message implements SlackMessage
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public static function create(string $text): self
    {
        return new static($text);
    }

    public function __toString(): string
    {
        return $this->getResponse()->__toString();
    }

    public function getResponse(): JsonResponse
    {
        return response()->json(['text' => $this->text])->setStatusCode(200);
    }
}
