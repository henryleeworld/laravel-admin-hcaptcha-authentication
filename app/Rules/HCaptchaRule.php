<?php

namespace App\Rules;

use App\Http\Integrations\HCaptcha\HCaptchaConnector;
use Illuminate\Contracts\Validation\Rule;

class HCaptchaRule implements Rule
{
    protected $hCaptchaConnector;

    protected array $messages = [];

    /**
     * HCaptcha.
     */
    public function __construct(HCaptchaConnector $hCaptchaConnector)
    {
        $this->hCaptchaConnector = $hCaptchaConnector;
    }

    public function passes($attribute, $value)
    {
        return $this->hCaptchaConnector->verifyResponse($value, request()->ip());
    }

    public function message()
    {
        return $this->messages;
    }
}
