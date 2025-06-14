<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini jika belum ada

class AuthController extends Controller
{
    /**
     * Handle user registration.
     */
    public function register(Request $request): JsonResponse
    {
        // Validasi input dari user
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nomor_telepon' => 'required|string|max:15',
            'path_sim' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Mengelola upload file SIM
        $path_sim = null;
        if ($request->hasFile('path_sim')) {
            $path_sim = $request->file('path_sim')->store('public/sims');
        }

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'path_sim' => $path_sim,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'role' => 'penyewa', // Otomatis set role sebagai penyewa
        ]);

        // Membuat token untuk user yang baru mendaftar
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil!',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    /**
     * Handle user login.
     */
    public function login(Request $request): JsonResponse
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Coba untuk melakukan autentikasi
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah.'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        
        // Periksa apakah rolenya adalah penyewa
        if ($user->role !== 'penyewa') {
             return response()->json([
                'success' => false,
                'message' => 'Login gagal, akun bukan penyewa.'
            ], 403);
        }

        // Buat token untuk user
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 200);
    }

}