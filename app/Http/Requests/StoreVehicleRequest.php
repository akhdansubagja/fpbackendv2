<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Untuk saat ini kita izinkan semua request,
        // nantinya ini bisa diisi logika otorisasi untuk admin
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
            'merk' => 'required|string|max:255', // Diubah dari 'jenis'
            'nama' => 'required|string|max:255',
            'transmisi' => ['required', Rule::in(['manual', 'matic'])],
            'jumlah_kursi' => 'required|integer|min:1', // Baru
            'bahan_bakar' => ['required', Rule::in(['bensin', 'diesel', 'listrik', 'hybrid'])], // Baru
            'has_ac' => 'required|boolean', // Baru
            'harga_sewa_harian' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'status' => ['required', Rule::in(['tersedia', 'disewa', 'servis'])],
            'foto_utama' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Diubah dari 'foto'

            // Aturan untuk galeri foto (opsional, berupa array)
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi setiap file dalam array
        ];
    }
}