<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut ini digunakan oleh kelas validator.
    | Beberapa aturan memiliki beberapa versi seperti aturan ukuran.
    | Jangan ragu untuk menyesuaikan setiap pesan di sini.
    |
    */

    'accepted'        => ':attribute harus diterima.',
    'confirmed'       => 'Konfirmasi :attribute tidak cocok.',
    'email'           => ':attribute harus berupa alamat email yang valid.',
    'min'             => [
        'string'  => ':attribute harus memiliki setidaknya :min karakter.',
    ],
    'required'        => 'Kolom :attribute wajib diisi.',
    'unique'          => ':attribute sudah ada sebelumnya.',

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi Kustom
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi kustom untuk atribut
    | menggunakan konvensi "attribute.rule" untuk menamai baris.
    | Ini membuatnya cepat untuk menentukan baris bahasa kustom spesifik
    | untuk aturan atribut tertentu.
    |
    */

    'custom' => [
        'email' => [
            'unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
        ],
        'nomor_telepon' => [
            'unique' => 'Nomor telepon ini sudah terdaftar. Silakan gunakan nomor lain.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atribut Validasi Kustom
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar placeholder atribut
    | dengan sesuatu yang lebih mudah dibaca seperti "Alamat E-Mail"
    | daripada "email". Ini hanya membantu kita membuat pesan lebih ekspresif.
    |
    */

    'attributes' => [
        'name' => 'Nama',
        'email' => 'Alamat Email',
        'password' => 'Password',
        'nomor_telepon' => 'Nomor Telepon',
        'alamat' => 'Alamat',
        'tanggal_lahir' => 'Tanggal Lahir',
        'path_sim' => 'Foto SIM',
    ],
];