<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWeddingInterviewRequest extends FormRequest
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
            'current_step' => ['required', 'integer', 'between:1,4'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:40'],
            'support_type' => ['required', 'in:full,shared,advisory'],
            'authorized_capacity' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'estimated_budget' => ['nullable', 'numeric', 'min:0'],
            'timezone' => ['required', 'timezone'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['boolean'],
        ];
    }
}
