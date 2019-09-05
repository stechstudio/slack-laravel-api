<?php declare(strict_types=1);

/**
 * Package: slack-laravel-api
 * Create Date: 2019-02-21
 * Created Time: 16:04
 */

namespace STS\Slack\Messaging;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use STS\Slack\Contracts\Messaging\LayoutBlock;
use STS\Slack\Contracts\Messaging\Message as SlackMessage;
use STS\Slack\Messaging\CompositionObjects\Text;
use STS\Slack\Messaging\LayoutBlocks\Context;
use STS\Slack\Messaging\LayoutBlocks\Divider;
use STS\Slack\Messaging\LayoutBlocks\Image;
use STS\Slack\Messaging\LayoutBlocks\Section;
use Symfony\Component\HttpFoundation\Response;
use function collect;

class Message implements SlackMessage
{
    /**
     * The usage of this field changes depending on whether you're using blocks or not. If you are, this is used as a
     * fallback string to display in notifications. If you aren't, this is the main body text of the message. It can be
     * formatted as plain text, or with mrkdwn. This field is not enforced as required when using blocks, however it is
     * highly recommended that you include it as the aforementioned fallback.
     *
     * @var string
     */
    protected $text;

    /**
     * An collection of layout blocks
     *
     * @var Collection
     */
    protected $blocks;

    /**
     * The ID of the conversation that the message should be published to.
     * This field is not used by incoming webhooks or when sending an interaction
     * response via request_url (as these are uniquely tied to a specific channel already).
     *
     * @var string
     */
    protected $channel;

    /**
     * An array of legacy secondary attachments.
     * We recommend you use blocks instead.
     *
     * @deprecated
     *
     * @var Collection
     */
    protected $attachments;

    /**
     * The ID of another un-threaded message to reply to.
     *
     * @var string
     */
    protected $threadTS;

    /**
     * Determines whether the text field is rendered according to mrkdwn formatting or not.
     * Defaults to true.
     *
     * @var bool
     */
    protected $markDown;

    /**
     * A special feature to response messages - when responding with a JSON payload you can directly control whether
     * the message will be visible only to the user who triggered the command (we call these ephemeral messages), or
     * visible to all members of the channel where the command was triggered.
     *
     * @var string
     */
    protected $responseType = 'ephemeral';

    public function __construct(
        string $text,
        string $channel = '',
        array $blocks = [],
        array $attachments = [],
        string $threadTS = '',
        bool $markDown = true
    ) {
        $this->text = $text;
        $this->channel = $channel;
        $this->blocks = collect($blocks);
        $this->attachments = collect($attachments);
        $this->threadTS = $threadTS;
        $this->markDown = $markDown;
    }

    public static function create(
        string $channel,
        string $text,
        array $blocks = [],
        array $attachments = [],
        string $threadTS = '',
        bool $markDown = true
    ): self {
        return new static($text, $channel, $blocks, $attachments, $threadTS, $markDown);
    }

    /**
     * Places the block at the end of the current set of blocks.
     */
    public function push(LayoutBlock $block): self
    {
        $this->blocks->push($block);
        return $this;
    }

    /**
     * Allows caller to provide an optional callback to further customize the block before it is pushed
     */
    public function pushWithCallback(LayoutBlock $block, ?callable $callback = null): self
    {
        if($callback != null) {
            $callback($block, $this);
        }

        return $this->push($block);
    }

    /**
     * Places the block at the beginning of the current set of blocks.
     */
    public function prepend(LayoutBlock $block): self
    {
        $this->blocks->prepend($block);
        return $this;
    }

    /**
     * Removes and returns the first block in the current set of blocks.
     */
    public function shift(): LayoutBlock
    {
        return $this->blocks->shift();
    }

    /**
     * Returns an array of the blocks in this message.
     */
    public function all(): array
    {
        return $this->blocks->all();
    }

    /**
     * Returns the total number of blocks in the message
     */
    public function count(): int
    {
        return $this->blocks->count();
    }

    /**
     * The pop method removes and returns the last block from the message
     */
    public function pop(): LayoutBlock
    {
        return $this->blocks->pop();
    }

    /**
     * The each method iterates over the blocks in the message and passes each item to a callback:
     */
    public function each(callable $callback): void
    {
        $this->blocks->each($callback);
    }

    /**
     * The every method may be used to verify that all blocks of a message pass a given truth test.
     * If the collection is empty, every will return true.
     */
    public function every(callable $callback): bool
    {
        return $this->blocks->every($callback);
    }

    /**
     * Appends an array of blocks to the end of the current blocks
     */
    public function concat(LayoutBlock ...$blocks): self
    {
        $this->blocks->concat($blocks);
        return $this;
    }

    /**
     * Converts this message to a string.
     */
    public function __toString(): string
    {
        return $this->getResponse()->__toString();
    }

    /**
     * Generates and returns a Json Response object
     * with out Slack Message as the payload.
     */
    public function getResponse(): Response
    {
        return response()->json($this->toSlackObjectArray())->setStatusCode(200);
    }

    /**
     * Converts all the message objects and parts to an array representation.
     */
    public function toSlackObjectArray(): array
    {
        $message = [
            'text' => $this->text,
            'mrkdwn' => $this->markDown,
            'response_type' => $this->responseType,
        ];

        if ($this->hasChannel()) {
            $message['channel'] = $this->channel;
        }

        if ($this->hasBlocks()) {
            $blocks = [];
            /** @var LayoutBlock $block */
            foreach ($this->blocks as $block) {
                $blocks[] = $block->toSlackObjectArray();
            }
            $message['blocks'] = $blocks;
        }

        if ($this->hasAttachments()) {
            $attachments = [];
            foreach ($this->attachments as $attachment) {
                $attachments[] = $attachment;
            }
            $message['attachments'] = $attachments;
        }

        return $message;
    }

    /**
     * Whether this message has a channel
     */
    public function hasChannel(): bool
    {
        return ! empty($this->channel);
    }

    /**
     * Whether or not this message has blocks.
     */
    public function hasBlocks(): bool
    {
        return $this->blocks->isNotEmpty();
    }

    /**
     * Whether or not this message has attachments
     */
    public function hasAttachments(): bool
    {
        return $this->attachments->isNotEmpty();
    }

    /**
     * Get the main body text of the message, or the fallback string if you have blocks
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the main body text of the message, or the fallback string if you have blocks
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     *  Get the array of layout blocks
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    /**
     *  Set the array of layout blocks
     */
    public function setBlocks(Collection $blocks): self
    {
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * Get the channel for this message
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * Set the channel for this message
     */
    public function setChannel(string $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * Just don't, but if you do, it's on you.
     * You were freakin' warned.
     *
     * @deprecated
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    /**
     * Just don't, but if you do, it's on you.
     * You were freakin' warned.
     *
     * @deprecated
     */
    public function setAttachments(array $attachments): self
    {
        $this->attachments = collect($attachments);
        return $this;
    }

    /**
     * Get the Thread Time Stamp for this message.
     */
    public function getThreadTS(): string
    {
        return $this->threadTS;
    }

    /**
     * Set the Thread Time Stamp for this message
     */
    public function setThreadTS(string $threadTS): self
    {
        $this->threadTS = $threadTS;
        return $this;
    }

    /**
     * Whether or not the text in the message has markdown annotations.
     */
    public function hasMarkDown(): bool
    {
        return $this->markDown;
    }

    /**
     * Whether or not the text in the message has markdown annotations.
     * Defaults to True
     */
    public function setMarkDown(bool $markDown): self
    {
        $this->markDown = $markDown;
        return $this;
    }

    /**
     * Get the response type for this message
     */
    public function getResponseType(): string
    {
        return $this->responseType;
    }

    /**
     * Set the response type, there are only two valid options
     * `ephemeral` or `in_channel`
     */
    public function setResponseType(string $responseType): self
    {
        if ($responseType === 'ephemeral' || $responseType === 'in_channel') {
            $this->responseType = $responseType;
            return $this;
        }
        throw new InvalidArgumentException("[$responseType] is not a valid Response Type");
    }

    /**
     * Whether or not this message has a thread time stamp.
     */
    public function hasThreadTs(): bool
    {
        return ! empty($this->threadTS);
    }

    public function image(string $imageUrl, string $altText, ?Text $title = null): self
    {
        return $this->push(Image::create($imageUrl, $altText, $title));
    }

    public function section(string $text, ?callable $callback = null): self
    {
        return $this->pushWithCallback(Section::create($text), $callback);
    }

    public function divider(): self
    {
        return $this->push(Divider::create());
    }

    public function context(?callable $callback = null): self
    {
        return $this->pushWithCallback(Context::create(), $callback);
    }
}
