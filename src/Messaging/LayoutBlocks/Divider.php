<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:34
 */

namespace STS\Slack\Messaging\LayoutBlocks;

use STS\Slack\Contracts\Messaging\LayoutBlock;

class Divider implements LayoutBlock
{
    /** @var string */
    protected static $type = 'divider';

    public static function create(): self
    {
        return new static;
    }

    public function getType(): string
    {
        return self::$type;
    }

    public function toSlackObjectArray(): array
    {
        return [
            'type' => 'divider',
        ];
    }
}
