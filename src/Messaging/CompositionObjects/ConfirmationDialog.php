<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:36
 */

namespace STS\Slack\Messaging\CompositionObjects;

use STS\Slack\Contracts\Messaging\CompositionObject;

class ConfirmationDialog implements CompositionObject
{
    /**
     * A plain_text-only text object that defines the dialog's title.
     *
     * @var Text
     */
    protected $title;

    /**
     * A text object that defines the explanatory text that appears in the confirm dialog.
     *
     * @var Text
     */
    protected $text;

    /**
     *    A plain_text-only text object to define the text of the button that confirms the action.
     *
     * @var Text
     */
    protected $confirm;

    /**
     *    A plain_text-only text object to define the text of the button that cancels the action.
     *
     * @var Text
     */
    protected $deny;

    public function __construct(Text $title, Text $text, Text $confirm, Text $deny)
    {
        $this->title = $title->setType('plain_text');
        $this->text = $text;
        $this->confirm = $confirm->setType('plain_text');
        $this->deny = $deny->setType('plain_text');
    }

    public static function createFromString(string $title, string $text, string $confirm, string $deny): self
    {
        return new static(
            new Text($title),
            new Text($text),
            new Text($confirm),
            new Text($deny)
        );
    }

    public function getTitle(): Text
    {
        return $this->title;
    }

    public function setTitle(Text $title): ConfirmationDialog
    {
        $this->title = $title;
        return $this;
    }

    public function getText(): Text
    {
        return $this->text;
    }

    public function setText(Text $text): ConfirmationDialog
    {
        $this->text = $text;
        return $this;
    }

    public function getConfirm(): Text
    {
        return $this->confirm;
    }

    public function setConfirm(Text $confirm): ConfirmationDialog
    {
        $this->confirm = $confirm;
        return $this;
    }

    public function getDeny(): Text
    {
        return $this->deny;
    }

    public function setDeny(Text $deny): ConfirmationDialog
    {
        $this->deny = $deny;
        return $this;
    }

    public function toSlackObjectArray(): array
    {
        return [
            'title' => $this->title->toSlackObjectArray(),
            'text' => $this->text->toSlackObjectArray(),
            'confirm' => $this->confirm->toSlackObjectArray(),
            'deny' => $this->toSlackObjectArray(),
        ];
    }
}
