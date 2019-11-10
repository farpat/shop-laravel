<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class GoodPasswordRule implements Rule
{
    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Hasher $hasher, Request $request)
    {
        $this->hasher = $hasher;
        $this->user = $request->user();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->hasher->check($value, $this->user);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('auth.failed');
    }
}
