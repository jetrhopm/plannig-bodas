<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitRsvpRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'response' => ['required', 'in:confirmed,declined'],
            'attending_count' => ['required_if:response,confirmed', 'nullable', 'integer', 'min:1', 'max:1000'],
            'idempotency_key' => ['required', 'uuid'],
            'members' => ['nullable', 'array', 'max:1000'],
            'members.*.display_name' => ['nullable', 'string', 'max:120'],
            'members.*.attending' => ['nullable', 'boolean'],
            'members.*.dietary_restrictions' => ['nullable', 'string', 'max:1000'],
            'members.*.accessibility_needs' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
