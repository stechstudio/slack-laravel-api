<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:35
 */

namespace STS\Slack\Messaging\LayoutBlocks;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use STS\Slack\Contracts\Messaging\ContextElement;
use STS\Slack\Contracts\Messaging\LayoutBlock;
use function collect;

class Context implements LayoutBlock
{
    /** @var string */
    protected static $type = 'context';

    /**
     * An array of image elements and text objects.
     *
     * @var Collection
     */
    protected $elements;

    /**
     * A string that acts a unique identifier for a block, included in the response.
     * If not specified, one will be generated.
     *
     * @var string
     */
    protected $blockId;

    public function __construct()
    {
        $this->elements = collect();
        $this->blockId = Uuid::uuid4()->toString();
    }

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
        $context = [
            'type' => 'context',
        ];

        $this->elements->each(function (ContextElement $element) use (&$context): void {
            $context['elements'][] = $element->toSlackObjectArray();
        });

        return $context;
    }

    public function push(ContextElement $element): self
    {
        $this->elements->push($element);
        return $this;
    }
}
