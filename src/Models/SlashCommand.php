<?php declare(strict_types=1);

/**
 * Package: slack-sdk-php
 * Create Date: 2019-02-19
 * Created Time: 16:21
 */

namespace STS\Slack\Models;

use Illuminate\Support\Collection;
use STS\Slack\Exceptions\HandlerUndefined;
use function json_encode;
use STS\Slack\Messaging\Message;

class SlashCommand
{
    /** @var Collection */
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = collect($attributes);
    }

    public static function create(array $attributes): self
    {
        return new static($attributes);
    }

    public function message(?string $text = null) {
        return Message::create($this->getChannelId(), $text ?? $this->getText());
    }

    /**
     * This is a verification token, a deprecated feature that you shouldn't use
     * any more. It was used to verify that requests were legitimately being sent by
     * Slack to your app, but you should use the signed secrets functionality to do this instead.
     *
     * @deprecated
     */
    public function getToken(): string
    {
        return $this->attributes->get('token', '');
    }

    public function getTeamId(): string
    {
        return $this->attributes->get('team_id', '');
    }

    public function getTeamDomain(): string
    {
        return $this->attributes->get('team_domain', '');
    }

    public function getEnterpriseId(): string
    {
        return $this->attributes->get('enterprise_id', '');
    }

    public function getEnterpriseName(): string
    {
        return $this->attributes->get('enterprise_name', '');
    }

    public function getChannelId(): string
    {
        return $this->attributes->get('channel_id', '');
    }

    public function getChannelName(): string
    {
        return $this->attributes->get('channel_name', '');
    }

    public function getUserId(): string
    {
        return $this->attributes->get('user_id', '');
    }

    public function getUserName(): string
    {
        return $this->attributes->get('user_name', '');
    }

    /**
     * This is the part of the Slash Command after the command itself, and it can contain absolutely anything that
     * the user might decide to type. It is common to use this text parameter to provide extra context for the command.
     *
     * You can prompt users to adhere to a particular format by
     * showing them in the Usage Hint field when creating a command.
     */
    public function getText(): string
    {
        return $this->attributes->get('text', '');
    }

    /**
     * A URL that you can use to respond to the command.
     * It can be used up to 5 times, within 30 minutes of the command being invoked.
     */
    public function getResponseUrl(): string
    {
        return $this->attributes->get('response_url', '');
    }

    /**
     * If you need to respond to the command by opening a dialog, you'll need this trigger ID to
     * get it to work. You can use this ID with dialog.open up to 3000ms after this data payload is sent.
     */
    public function getTriggerId(): string
    {
        return $this->attributes->get('trigger_id', '');
    }

    /**
     * Make it trivial for the slash command to dispatch itself.
     *
     * @return mixed
     */
    public function dispatch()
    {
        if ($this->hasHandler()) {
            return app()->make('SlashCommandDispatcher')->dispatch($this);
        }
        throw new HandlerUndefined(sprintf('[%s] is not a valid command.', $this->getCommand()));
    }

    /**
     * Determines if we have a handler registered for this Command
     */
    public function hasHandler(): bool
    {
        return app()->make('SlashCommandDispatcher')->hasHandler($this->getCommand());
    }

    /**
     *  The command that was typed in to trigger this request.
     *  this value can be useful if you want to use a single Request URL
     *  to service multiple Slash Commands, as it lets you tell them apart.
     */
    public function getCommand(): string
    {
        return $this->attributes->get('command', '');
    }

    public function toJson(): string
    {
        return json_encode($this->attributes);
    }

    public function all(): Collection
    {
        return $this->attributes;
    }
}
