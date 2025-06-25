<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Pastikan ini ada di atas

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
            'delivery_option' => ['required', Rule::in(['pickup', 'delivered'])],
            'delivery_address' => ['nullable', 'required_if:delivery_option,delivered', 'string', 'max:255'],
            'payment_method' => ['required', Rule::in(['transfer', 'qris', 'bayar_di_tempat'])],
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}