<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-02-22
 * Created Time: 13:13
 */

namespace STS\Slack\Contracts\Messaging;

interface ContextElement
{
    /**
     * Convert the object to an array representation of the slack object.
     */
    public function toSlackObjectArray(): array;
}
