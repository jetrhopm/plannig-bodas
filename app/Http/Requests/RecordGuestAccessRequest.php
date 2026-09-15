<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class RecordGuestAccessRequest extends FormRequest { public function authorize(): bool { return $this->user() !== null; } public function rules(): array { return ['count' => ['required', 'integer', 'min:1'], 'idempotency_key' => ['required', 'uuid']]; } }
