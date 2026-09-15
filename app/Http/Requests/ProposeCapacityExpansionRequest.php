<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProposeCapacityExpansionRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->role === 'admin'; }
    public function rules(): array
    {
        return ['additional_cost' => ['required', 'numeric', 'min:0'], 'decision_reason' => ['nullable', 'string', 'max:1000']];
    }
}
