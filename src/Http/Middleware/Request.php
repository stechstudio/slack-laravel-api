<?php declare(strict_types=1);

namespace STS\Slack\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\Request as LaravelRequest;
use STS\Slack\Exceptions\InvalidRequest;
use STS\Slack\Exceptions\Timeout;

class Request
{
    /** @var string */
    protected $slackSigningVersion = 'v0';

    /** @var string */
    protected $timestamp = '';

    /** @var int */
    protected $timeOutInMinutes = 5;

    /**
     * Validate a slack request
     * via the slack signing secret (not the token)
     *
     * @throws Exception
     */
    public function handle(LaravelRequest $request, Closure $next): LaravelRequest
    {
        $this->verifySignatureIsPresent($request);

        $this->verifyTimestamp($request);

        if ($request->header('X-Slack-Signature') !== $this->generateLocalSignature($request)) {
            throw new InvalidRequest('Signatures do not match');
        }

        return $next($request);
    }

    protected function verifySignatureIsPresent(LaravelRequest $request): void
    {
        if ($request->header('X-Slack-Signature', null) === null) {
            throw new InvalidRequest('No Slack Signature Present.');
        }
    }

    public function verifyTimestamp(LaravelRequest $request): void
    {
        $this->verifyTimestampIsPresent($request);

        $this->timestamp = $request->header('X-Slack-Request-Timestamp');

        if (Carbon::now()->diffInMinutes(Carbon::createFromTimestamp((int) $this->timestamp)) > $this->timeOutInMinutes) {
            throw new Timeout('Timestamp too old. Possible replay attack.');
        }
    }

    public function verifyTimestampIsPresent(LaravelRequest $request): void
    {
        if ($request->header('X-Slack-Request-Timestamp', null) === null) {
            throw new InvalidRequest('No Slack Request Timestamp Present.');
        }
    }

    protected function generateLocalSignature(LaravelRequest $request): string
    {
        $signatureString = sprintf(
            '%s:%s:%s',
            $this->slackSigningVersion,
            $this->timestamp,
            $request->getContent()
        );
        return sprintf(
            '%s=%s',
            $this->slackSigningVersion,
            hash_hmac('sha256', $signatureString, config('slack.signing_secret'))
        );
    }
}
