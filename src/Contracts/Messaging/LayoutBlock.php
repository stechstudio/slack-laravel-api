<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 14:49
 */

namespace STS\Slack\Contracts\Messaging;

interface LayoutBlock
{

    /**
     * Convert the object to an array representation of the slack object.
     */
    public function toSlackObjectArray(): array;

    /**
     * Get the type of block element
     */
    public function getType(): string;

    /**
     * Set the type of block element
     */
    public function setType(): BlockElement;
}
