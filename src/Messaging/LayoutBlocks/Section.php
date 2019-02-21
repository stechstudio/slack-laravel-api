<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:34
 */

namespace STS\Slack\Messaging\LayoutBlocks;

use STS\Slack\Contracts\Messaging\LayoutBlock;

class Section implements LayoutBlock
{
    protected $type;
    protected $text;
    protected $blockId;
    protected $fields;
    protected $accessory;

}
