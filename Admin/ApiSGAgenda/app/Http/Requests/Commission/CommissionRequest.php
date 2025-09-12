<?php

namespace App\Http\Requests\Commission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CommissionRequest extends FormRequest
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
            'ownerCode' => ['required'],
            'serviceCode' => ['nullable', 'required_without:categoryCode'],
            'categoryCode' => ['nullable', 'required_without:serviceCode'],
            'attendantCode' => ['required'],
            'percCommission' => ['required', 'numeric'],
            'fixCommission' => ['required', 'numeric'],
        ];
    }
}
