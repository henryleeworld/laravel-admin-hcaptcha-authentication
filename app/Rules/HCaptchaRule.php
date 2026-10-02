<?php

namespace App\Rules;

use App\Http\Integrations\HCaptcha\HCaptchaConnector;
use Illuminate\Contracts\Validation\Rule;

class HCaptchaRule implements Rule
{
    protected array $messages = [];

    /**
     * Constructor.
     */
    public function __construct(protected HCaptchaConnector $hCaptchaConnector)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        return $this->hCaptchaConnector->verifyResponse($value, request()->ip());
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return $this->messages;
    }
}
