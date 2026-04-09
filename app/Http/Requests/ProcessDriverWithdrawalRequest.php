<?php

namespace App\Http\Requests;

use App\Models\DriverWithdrawal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessDriverWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([
                DriverWithdrawal::STATUS_APPROVED,
                DriverWithdrawal::STATUS_REJECTED,
            ])],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'transfer_reference' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($this->input('status') === DriverWithdrawal::STATUS_REJECTED && blank($this->input('admin_notes'))) {
                $validator->errors()->add('admin_notes', 'Catatan admin wajib diisi saat withdraw ditolak.');
            }
        });
    }
}
