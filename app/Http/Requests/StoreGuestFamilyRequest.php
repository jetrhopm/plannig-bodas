<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuestFamilyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('wedding')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:120'],
            'responsible_name' => ['nullable', 'string', 'max:120'],
            'responsible_email' => ['nullable', 'email', 'max:255'],
            'responsible_phone' => ['nullable', 'string', 'max:40'],
            'allocation' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
