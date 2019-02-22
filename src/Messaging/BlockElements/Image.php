<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 19:13
 */

namespace STS\Slack\Messaging\BlockElements;

use STS\Slack\Contracts\Messaging\BlockElement;
use STS\Slack\Contracts\Messaging\ContextElement;

class Image implements BlockElement, ContextElement
{
    /** @var string */
    protected static $type = 'image';

    /**
     * The URL of the image to be displayed.
     *
     * @var string
     */
    protected $imageUrl;

    /**
     * A plain-text summary of the image. This should not contain any markup.
     *
     * @var string
     */
    protected $altText;

    public function __construct(string $url, string $altText)
    {
        $this->imageUrl = $url;
        $this->altText = $altText;
    }

    public static function create(string $url, string $altText): self
    {
        return new static($url, $altText);
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

    public function getType(): string
    {
        return self::$type;
    }

    public function toSlackObjectArray(): array
    {
        return [
            'type' => 'image',
            'image_url' => $this->imageUrl,
            'alt_text' => $this->altText,
        ];
    }
}
