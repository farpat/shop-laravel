<?php

namespace App\Http\Requests;

use App\Rules\GoodPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class UserPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'password'     => ['required', app(GoodPasswordRule::class)],
            'new_password' => 'required|string|min:6|confirmed',
        ];
    }
}
