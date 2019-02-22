<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:34
 */

namespace STS\Slack\Messaging\LayoutBlocks;

use Ramsey\Uuid\Uuid;
use STS\Slack\Contracts\Messaging\LayoutBlock;
use STS\Slack\Messaging\CompositionObjects\Text;

class Image implements LayoutBlock
{
    /** @var string */
    protected static $type = 'image';

    /**
     * A string that acts a unique identifier for a block, included in the response.
     * If not specified, one will be generated.
     *
     * @var string
     */
    protected $blockId;
    /**
     *    The URL of the image to be displayed.
     *
     * @var string
     */
    private $imageUrl;
    /**
     *    A plain-text summary of the image. This should not contain any markup.
     *
     * @var string
     */
    private $altText;
    /**
     * An optional title for the image in the form of a text object that can only be of type: plain_text.
     *
     * @var Text
     */
    private $title;


    public function __construct(string $imageUrl, string $altText, ?Text $title = null)
    {
        $this->imageUrl = $imageUrl;
        $this->altText = $altText;
        $this->title = $title;
        $this->blockId = Uuid::uuid4()->toString();
    }

    public static function create(string $imageUrl, string $altText, ?Text $title = null): self
    {
        return new static($imageUrl, $altText, $title);
    }

    public function getBlockId(): string
    {
        return $this->blockId;
    }

    public function setBlockId(string $blockId): Image
    {
        $this->blockId = $blockId;
        return $this;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): Image
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    public function setAltText(string $altText): Image
    {
        $this->altText = $altText;
        return $this;
    }

    public function getTitle(): Text
    {
        return $this->title;
    }

    public function setTitle(Text $title): Image
    {
        $this->title = $title;
        return $this;
    }

    public function getType(): string
    {
        return self::$type;
    }

    public function toSlackObjectArray(): array
    {
        $image = [
            'type' => 'image',
            'block_id' => $this->blockId,
            'image_url' => $this->imageUrl,
            'alt_text' => $this->altText,
        ];

        if ($this->hasTitle()) {
            $image['title'] = $this->title->toSlackObjectArray();
        }

        return $image;
    }

    public function hasTitle(): bool
    {
        return ! empty($this->title);
    }
}
