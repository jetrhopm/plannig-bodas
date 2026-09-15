<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RespondCapacityExpansionProposalRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array
    {
        return ['decision' => ['required', 'in:accepted,rejected'], 'decision_reason' => ['nullable', 'string', 'max:1000']];
    }
}
