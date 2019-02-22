<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:36
 * See: https://api.slack.com/reference/messaging/composition-objects#text
 */

namespace STS\Slack\Messaging\CompositionObjects;

use STS\Slack\Contracts\Messaging\CompositionObject;
use STS\Slack\Contracts\Messaging\ContextElement;

class Text implements CompositionObject, ContextElement
{
    /**
     * The formatting to use for this text object.
     * Can be one of `plain_text` or `mrkdwn`.
     *
     * @var string
     */
    protected $type = 'mrkdwn';

    /**
     * The text for the block. This field accepts any of
     * the standard text formatting markup when type is mrkdwn.
     *
     * @var string
     */
    protected $text;

    /**
     * Indicates whether emojis in a text field should be escaped
     * into the colon emoji format. This field is only usable when type is plain_text
     *
     * @var bool
     */
    protected $emoji = false;

    /**
     * When set to false (as is default) URLs will be auto-converted into links,
     * conversation names will be link-ified, and certain mentions will be automatically parsed.
     * Using a value of false will skip any preprocessing of this nature, although you can still
     * include manual parsing strings. This field is only usable when type is mrkdwn.
     *
     * @var bool
     */
    protected $verbatim = false;

    public function __construct(string $text, string $type = 'mrkdwn', bool $emoji = false, bool $verbatim = false)
    {
        $this->text = $text;
        $this->type = $type;
        $this->emoji = $emoji;
        $this->verbatim = $verbatim;
    }

    public static function create(
        string $text,
        string $type = 'mrkdwn',
        bool $emoji = false,
        bool $verbatim = false
    ): self {
        return new static($text, $type, $emoji, $verbatim);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Text
    {
        $this->type = $type;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): Text
    {
        $this->text = $text;
        return $this;
    }

    public function isEmoji(): bool
    {
        return $this->emoji;
    }

    public function setEmoji(bool $emoji): Text
    {
        $this->emoji = $emoji;
        return $this;
    }

    public function isVerbatim(): bool
    {
        return $this->verbatim;
    }

    public function setVerbatim(bool $verbatim): Text
    {
        $this->verbatim = $verbatim;
        return $this;
    }

    public function toSlackObjectArray(): array
    {
        $object = [
            'type' => $this->type,
            'text' => $this->text,
        ];

        if ($this->type === 'mrkdwn') {
            $object['verbatim'] = $this->verbatim;
        }

        if ($this->type === 'plain_text') {
            $object['emoji'] = $this->emoji;
        }

        return $object;
    }
}
