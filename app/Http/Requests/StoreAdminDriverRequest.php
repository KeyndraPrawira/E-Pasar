<?php

namespace App\Http\Requests;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id', Rule::unique('drivers', 'user_id')],
            'nomor_kendaraan' => ['required', 'string', 'max:20', 'unique:drivers,nomor_kendaraan'],
            'jenis_kendaraan' => ['required', 'string', 'max:50'],
            'nomor_stnk' => ['required', 'string', 'max:50', 'unique:drivers,nomor_stnk'],
            'nomor_sim' => ['required', 'string', 'max:50', 'unique:drivers,nomor_sim'],
            'foto_ktp' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_sim' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_stnk' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kendaraan' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_diri' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', Rule::in([Driver::STATUS_PENDING, Driver::STATUS_APPROVED, Driver::STATUS_REJECTED])],
            'verification_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User driver wajib dipilih.',
            'user_id.exists' => 'User tidak ditemukan.',
            'user_id.unique' => 'User sudah memiliki data driver.',
            'nomor_kendaraan.required' => 'Nomor kendaraan wajib diisi.',
            'nomor_kendaraan.unique' => 'Nomor kendaraan sudah terdaftar.',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nomor_stnk.required' => 'Nomor STNK wajib diisi.',
            'nomor_stnk.unique' => 'Nomor STNK sudah terdaftar.',
            'nomor_sim.required' => 'Nomor SIM wajib diisi.',
            'nomor_sim.unique' => 'Nomor SIM sudah terdaftar.',
            'foto_ktp.required' => 'Foto KTP wajib diunggah.',
            'foto_sim.required' => 'Foto SIM wajib diunggah.',
            'foto_stnk.required' => 'Foto STNK wajib diunggah.',
            'foto_kendaraan.required' => 'Foto kendaraan wajib diunggah.',
            'foto_diri.required' => 'Foto diri wajib diunggah.',
            'status.required' => 'Status driver wajib dipilih.',
            'status.in' => 'Status driver tidak valid.',
        ];
    }
}
