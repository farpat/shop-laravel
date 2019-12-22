<?php

namespace App\Http\Requests;

use App\Models\Billing;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ViewBillingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        /** @var Billing $billing */
        $billing = $this->route('billing');

        /** @var User $currentUser */
        $currentUser = $this->user();

        return $currentUser->id === $billing->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            //
        ];
    }
}
