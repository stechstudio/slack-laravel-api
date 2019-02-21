<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-02-21
 * Created Time: 16:04
 */

namespace STS\Slack\Messaging;

class Message
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function toSlackObjectArray(): array
    {

    }
}
