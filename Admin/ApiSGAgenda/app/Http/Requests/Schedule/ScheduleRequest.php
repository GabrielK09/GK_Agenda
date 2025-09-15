<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'siteName' => ['required', 'string'],
            'serviceCode' => ['required'],
            'attendantCode' => ['required'],
            'customerName' => ['required', 'string'],
            'customerPhone' => ['required', 'string'],
            'date' => ['required'],
            'hour' => ['required'],

        ];
    }
}
