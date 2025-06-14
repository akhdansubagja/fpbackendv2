<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan semua user yang terautentikasi
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
            'waktu_sewa' => 'required|date|after_or_equal:today',
            'waktu_kembali' => 'required|date|after:waktu_sewa',
        ];
    }
}