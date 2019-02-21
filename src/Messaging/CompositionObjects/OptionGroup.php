<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 17:37
 */

namespace STS\Slack\Messaging\CompositionObjects;

/**
 * Provides a way to group options in a select menu.
 */
class OptionGroup
{
    /**
     *    A plain_text only text object that defines the label shown above this group of options.
     *
     * @var Text
     */
    protected $label;
    /**
     * An array of option objects that belong to this specific group.
     *
     * @var array
     */
    protected $options;

    public function __construct(Text $label, Option ...$options)
    {
        $this->label = $label;
        $this->options = $options;
    }

    public function getLabel(): Text
    {
        return $this->label;
    }

    public function setLabel(Text $label): OptionGroup
    {
        $this->label = $label;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(Option ...$options): OptionGroup
    {
        $this->options = $options;
        return $this;
    }

    public function push(Option $option): OptionGroup
    {
        array_push($this->options, $option);
    }

    public function toSlackObjectArray(): array
    {
        $response = [
            'label' => $this->label->toSlackObjectArray(),
            'options' => [],
        ];

        foreach ($this->options as $option) {
            array_push($response['options'], $option->toSlackObjectArray());
        }

        return $response;
    }
}
