<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1'],
            'bank_name' => ['required', 'string'],
            'account_name' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'requested_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Nominal withdraw wajib diisi.',
            'amount.integer' => 'Nominal withdraw harus berupa angka.',
            'amount.min' => 'Nominal withdraw minimal 1 rupiah.',
            'bank_name.required' => 'Nama bank wajib diisi.',
            'account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
        ];
    }
}
