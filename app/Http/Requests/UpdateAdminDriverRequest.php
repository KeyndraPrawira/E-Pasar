<?php

namespace App\Http\Requests;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $driverId = $this->route('driver')?->id;

        return [
            'nomor_kendaraan' => [
                'required',
                'string',
                'max:20',
                Rule::unique('drivers', 'nomor_kendaraan')->ignore($driverId),
            ],
            'jenis_kendaraan' => ['required', 'string', 'max:50'],
            'nomor_stnk' => [
                'required',
                'string',
                'max:50',
                Rule::unique('drivers', 'nomor_stnk')->ignore($driverId),
            ],
            'nomor_sim' => [
                'required',
                'string',
                'max:50',
                Rule::unique('drivers', 'nomor_sim')->ignore($driverId),
            ],
            'foto_ktp' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_sim' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_stnk' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kendaraan' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_diri' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', Rule::in([Driver::STATUS_PENDING, Driver::STATUS_APPROVED, Driver::STATUS_REJECTED])],
            'verification_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_kendaraan.required' => 'Nomor kendaraan wajib diisi.',
            'nomor_kendaraan.unique' => 'Nomor kendaraan sudah terdaftar.',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nomor_stnk.required' => 'Nomor STNK wajib diisi.',
            'nomor_stnk.unique' => 'Nomor STNK sudah terdaftar.',
            'nomor_sim.required' => 'Nomor SIM wajib diisi.',
            'nomor_sim.unique' => 'Nomor SIM sudah terdaftar.',
            'status.required' => 'Status driver wajib dipilih.',
            'status.in' => 'Status driver tidak valid.',
        ];
    }
}
