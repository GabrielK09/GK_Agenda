<?php

namespace App\Http\Requests\Attendant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AttendantHoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ownerCode' => ['required', 'exists:owners,owner_code'],
            'attendantCode' => ['required', 'exists:attendants,attendant_code'],
            'hours' => ['required', 'array']
        ];
    }
}
