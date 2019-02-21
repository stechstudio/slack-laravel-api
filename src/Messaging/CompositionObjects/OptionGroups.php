<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-20
 * Created Time: 19:01
 */

namespace STS\Slack\Messaging\CompositionObjects;

class OptionGroups
{
    /**
     * A ... um ... group of options.
     *
     * @var array
     */
    protected $groups;

    public function __construct(OptionGroup ...$groups)
    {
        $this->groups = $groups;
    }

    public function add(OptionGroup $group): self
    {
        array_push($this->groups, $group);
        return $this;
    }

    public function remove(OptionGroup $group): self
    {
        foreach ($this->groups as $k => $v) {
            if ($v === $group) {
                unset($this->groups[$k]);
            }
        }
        return $this;
    }

    public function toSlackObjectArray(): array
    {
        $response = [
            'option_groups' => [],
        ];

        foreach ($this->groups as $group) {
            array_push($response['option_groups'], $group->toSlackObjectArray());
        }

        return $response;
    }
}
