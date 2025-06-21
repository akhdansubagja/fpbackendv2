<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'merk' => 'sometimes|string|max:255',
            'nama' => 'sometimes|string|max:255',
            'transmisi' => ['sometimes', Rule::in(['manual', 'matic'])],
            'jumlah_kursi' => 'sometimes|integer|min:1',
            'bahan_bakar' => ['sometimes', Rule::in(['bensin', 'diesel', 'listrik', 'hybrid'])],
            'has_ac' => 'sometimes|boolean',
            'harga_sewa_harian' => 'sometimes|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0', // <-- TAMBAHKAN BARIS INI
            'deskripsi' => 'sometimes|string',
            'status' => ['sometimes', Rule::in(['tersedia', 'disewa', 'servis'])],
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}