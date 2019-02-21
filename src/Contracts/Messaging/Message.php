<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-02-21
 * Created Time: 16:33
 */

namespace STS\Slack\Contracts\Messaging;

use Illuminate\Http\JsonResponse;

interface Message
{
    public function __toString(): string;

    public function getResponse(): JsonResponse;
}
