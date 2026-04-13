<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDriverApplicationRequest extends FormRequest
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
        $driverId = $this->user()?->driver?->id;

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
            'foto_ktp' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_sim' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_stnk' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kendaraan' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_diri' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
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
            'foto_ktp.required' => 'Foto KTP wajib diunggah.',
            'foto_ktp.image' => 'Foto KTP harus berupa gambar.',
            'foto_sim.required' => 'Foto SIM wajib diunggah.',
            'foto_sim.image' => 'Foto SIM harus berupa gambar.',
            'foto_stnk.required' => 'Foto STNK wajib diunggah.',
            'foto_stnk.image' => 'Foto STNK harus berupa gambar.',
            'foto_kendaraan.required' => 'Foto kendaraan wajib diunggah.',
            'foto_kendaraan.image' => 'Foto kendaraan harus berupa gambar.',
            'foto_diri.required' => 'Foto diri wajib diunggah.',
            'foto_diri.image' => 'Foto diri harus berupa gambar.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nomor_kendaraan' => strtoupper((string) $this->nomor_kendaraan),
            'jenis_kendaraan' => ucfirst(strtolower((string) $this->jenis_kendaraan)),
        ]);
    }
}
