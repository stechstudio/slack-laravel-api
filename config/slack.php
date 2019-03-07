<?php declare(strict_types=1);

return [
    /**
     * On each HTTP request that Slack sends, they add
     * an X-Slack-Signature HTTP header. The signature is
     * created by combining the signing secret with the body of the
     * request we're sending using a standard HMAC-SHA256 keyed hash.
     * https://api.slack.com/docs/verifying-requests-from-slack#about
     */
    'signing_secret' => env('SLACK_SIGNING_SECRET'),
];
