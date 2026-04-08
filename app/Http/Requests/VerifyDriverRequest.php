<?php

namespace App\Http\Requests;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([Driver::STATUS_APPROVED, Driver::STATUS_REJECTED])],
            'verification_notes' => ['nullable', 'required_if:status,' . Driver::STATUS_REJECTED, 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status verifikasi wajib dipilih.',
            'status.in' => 'Status verifikasi tidak valid.',
            'verification_notes.required_if' => 'Catatan verifikasi wajib diisi saat pengajuan ditolak.',
            'verification_notes.max' => 'Catatan verifikasi maksimal 1000 karakter.',
        ];
    }
}
