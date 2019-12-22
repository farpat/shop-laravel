<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserInformationsRequest extends FormRequest
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
            'name'             => ['required', 'string', 'max:191'],
            'email'            => [
                'required', 'string', 'email', 'max:191', 'unique:users,email,' . $this->user()->id
            ],
            'addresses.*.text' => function ($attribute, $value, $fail) {
                $longitudeAttribute = str_replace('.text', '.longitude', $attribute);
                $longitudeValue = $this->input($longitudeAttribute);

                if ($longitudeValue === null) {
                    $fail(trans('validation.not_regex', ['attribute' => 'text']));
                }
            },
        ];
    }
}
