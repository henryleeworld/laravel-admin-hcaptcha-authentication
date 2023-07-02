<?php

namespace App\Rules;

use App\Services\HCaptchaService;
use Illuminate\Contracts\Validation\Rule;

class HCaptchaRule implements Rule
{
    protected $hCaptchaService;

    protected array $messages = [];

    /**
     * HCaptcha.
     */
    public function __construct(HCaptchaService $hCaptchaService)
    {
        $this->hCaptchaService = $hCaptchaService;
    }

    public function passes($attribute, $value)
    {
        return $this->hCaptchaService->verifyResponse($value, request()->ip());
    }

    public function message()
    {
        return $this->messages;
    }
}
