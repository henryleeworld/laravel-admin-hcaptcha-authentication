<?php

namespace App\Http\Integrations\HCaptcha;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class HCaptchaConnector
{
    const VERIFY_URL = 'https://hcaptcha.com/siteverify';

    /**
     * The hCaptcha secret key.
     *
     * @var string
     */
    protected string $secret;

    /**
     * The hCaptcha sitekey key.
     *
     * @var string
     */
    protected string $sitekey;

    /**
     * @var \GuzzleHttp\Client
     */
    protected Client $http;

    /**
     * The cached verified responses.
     *
     * @var array
     */
    protected array $verifiedResponses = [];

    /**
     * HCaptcha.
     */
    public function __construct()
    {
        $this->secret = config('services.hcaptcha.secret');
        $this->sitekey = config('services.hcaptcha.sitekey');
        $this->http = new Client(config('services.hcaptcha.options'));
    }

    /**
     * Verify hCaptcha response.
     *
     * @param string $response
     * @param string $clientIp
     */
    public function verifyResponse($response, $clientIp = null): bool
    {
        if (empty($response)) {
            return false;
        }

        // Return true if response already verfied before.
        if (in_array($response, $this->verifiedResponses)) {
            return true;
        }

        $verifyResponse = $this->sendRequestVerify([
            'secret'   => $this->secret,
            'response' => $response,
            'remoteip' => $clientIp,
        ]);

        if (isset($verifyResponse['success']) && $verifyResponse['success'] === true) {
            // A response can only be verified once from hCaptcha, so we need to
            // cache it to make it work in case we want to verify it multiple times.
            $this->verifiedResponses[] = $response;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Send verify request.
     *
     * @param array $query
     */
    protected function sendRequestVerify(array $query = []): array
    {
        $response = $this->http->request('POST', static::VERIFY_URL, [
            'form_params' => $query,
        ]);

        return json_decode($response->getBody(), true);
    }
}
