<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:34
 */

namespace STS\Slack\Messaging\LayoutBlocks;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use STS\Slack\Contracts\Messaging\BlockElement as BlockElementContract;
use STS\Slack\Contracts\Messaging\CompositionObject;
use STS\Slack\Contracts\Messaging\LayoutBlock;
use STS\Slack\Messaging\CompositionObjects\Text;
use function collect;

class Section implements LayoutBlock
{
    /**
     * The type of block. For a section block, type will always be section.
     *
     * @var string
     */
    protected static $type = 'section';

    /**
     * The text for the block, in the form of a text object.
     *
     * @var Text
     */
    protected $text;

    /**
     * A string that acts a unique identifier for a block, included in the response.
     * If not specified, one will be generated.
     *
     * @var string
     */
    protected $blockId;

    /**
     * An array of text objects. Any text objects included with fields will be
     * rendered in a compact format that allows for 2 columns of side-by-side text.
     *
     * @var Collection
     */
    protected $fields;

    /**
     * One of the available element objects.
     *
     * @var BlockElementContract
     */
    protected $accessory;

    public function __construct(Text $text, array $fields = [])
    {
        $this->text = $text;
        $this->fields = collect($fields);
        $this->blockId = Uuid::uuid4()->toString();
    }

    public static function create(string $text): self
    {
        return new static(Text::create($text));
    }

    public function getType(): string
    {
        return self::$type;
    }

    public function toSlackObjectArray(): array
    {
        $section = [
            'type' => 'section',
            'text' => $this->text->toSlackObjectArray(),
            'block_id' => $this->blockId,
        ];

        if ($this->fields->isNotEmpty()) {
            /** @var CompositionObject $field */
            foreach ($this->fields as $field) {
                $section['fields'][] = $field->toSlackObjectArray();
            }
        }

        if (! empty($this->accessory)) {
            $section['accessory'] = $this->accessory->toSlackObjectArray();
        }

        return $section;
    }

    public function getText(): Text
    {
        return $this->text;
    }

    public function setText(Text $text): Section
    {
        $this->text = $text;
        return $this;
    }

    public function getBlockId(): string
    {
        return $this->blockId;
    }

    public function setBlockId(string $blockId): Section
    {
        $this->blockId = $blockId;
        return $this;
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function setFields(array $fields): Section
    {
        $this->fields = collect($fields);
        return $this;
    }

    public function getAccessory(): BlockElementContract
    {
        return $this->accessory;
    }

    public function setAccessory(BlockElementContract $accessory): Section
    {
        $this->accessory = $accessory;
        return $this;
    }

    /**
     * Places the field at the end of the current set of fields.
     */
    public function push(Text $field): self
    {
        $this->fields->push($field);
        return $this;
    }

    /**
     * Places the field at the beginning of the current set of fields.
     */
    public function prepend(Text $field): self
    {
        $this->fields->prepend($field);
        return $this;
    }
}
