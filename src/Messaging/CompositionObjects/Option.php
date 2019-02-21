<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:37
 */

namespace STS\Slack\Messaging\CompositionObjects;

use STS\Slack\Contracts\Messaging\CompositionObject;

/**
 * An object that represents a single selectable item in a select menu.
 */
class Option implements CompositionObject
{
    /**
     * A plain_text only text object that defines the text shown in the option on the menu.
     *
     * @var Text
     */
    protected $text;
    /**
     * The string value that will be passed to your app when this option is chosen.
     *
     * @var string
     */
    protected $value;

    public function __construct(Text $text, string $value)
    {
        $this->text = $text->setType('plain_text');
        $this->value = $value;
    }

    public static function createFromString(string $text, string $value): self
    {
        return new static(
            new Text($text),
            $value
        );
    }

    public function getText(): Text
    {
        return $this->text;
    }

    public function setText(Text $text): Option
    {
        $this->text = $text;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): Option
    {
        $this->value = $value;
        return $this;
    }

    public function toSlackObjectArray(): array
    {
        return [
            'text' => $this->text->toSlackObjectArray(),
            'value' => $this->value,
        ];
    }
}
