<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ServicesManagementRequest extends FormRequest
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
        $required = $this->method('POST') ? 'required' : 'sometimes';
        return [
            'ownerCode' => [$required],
            'categoryCode' => ['nullable'],
            'name' => [$required, 'string'],
            'price' => [$required, 'numeric'],
            'description' => [$required, 'string'],
            'durationString' => [$required],
            'duration' => [$required],
            'isHomeService' => [$required],
            'checkAvailability' => [$required],
        ];
    }
}
