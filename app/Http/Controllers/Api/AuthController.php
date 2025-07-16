<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // <-- Impor ini
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; // <-- TAMBAHKAN INI
use App\Mail\PasswordResetMail;

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
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            // --- PERUBAHAN DI SINI ---
            // Menambahkan aturan 'unique:users,nomor_telepon' untuk memastikan nomor telepon tidak terdaftar
            'nomor_telepon' => 'required|string|max:15|unique:users,nomor_telepon',
            'path_sim' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nomor_rekening' => 'required|string|max:50',
        ]);

        // Jika validasi gagal, kembalikan response JSON dengan daftar error yang spesifik.
        // Kode ini sudah benar dan tidak perlu diubah.
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Mengelola upload file SIM
        $path_sim = null;
        if ($request->hasFile('path_sim')) {
            // Menggunakan store() akan menghasilkan nama file unik secara otomatis
            $path_sim = $request->file('path_sim')->store('sims', 'public');
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
            'nomor_rekening' => $request->nomor_rekening,
            'role' => 'penyewa',
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
            return response()->json(['errors' => $validator->errors()], 422);
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

    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Cek apakah password saat ini cocok
        if (!Hash::check($request->current_password, (string) $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini yang Anda masukkan salah.'
            ], 422);
        }

        // 3. Cek apakah password baru sama dengan password lama
        if (Hash::check($request->new_password, (string) $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama.'
            ], 422);
        }

        // 4. Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password Anda berhasil diperbarui.'
        ], 200);
    }

    /**
     * Handle a forgot password request.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Email tidak terdaftar.'], 404);
        }

        // Buat token yang akan disimpan di DB (di-hash) dan yang akan dikirim (tidak di-hash)
        $plainToken = Str::random(60);
        $hashedToken = Hash::make($plainToken);

        // Hapus token lama jika ada, lalu buat yang baru
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $hashedToken,
            'created_at' => Carbon::now()
        ]);

        // ===== LOGIKA PENGIRIMAN EMAIL =====
        try {
            Mail::to($request->email)->send(new PasswordResetMail($plainToken));
        } catch (\Exception $e) {
            // Jika email gagal dikirim, kembalikan response error
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email reset password. Silakan coba lagi nanti.'
            ], 500);
        }
        // ===================================

        return response()->json([
            'success' => true,
            'message' => 'Token reset password telah dikirim ke email Anda.'
        ]);
    }


    /**
     * Handle a reset password request.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cari data reset token di database
        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Cek jika token tidak ada atau tidak cocok
        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return response()->json(['success' => false, 'message' => 'Token reset password tidak valid.'], 400);
        }

        // Cek jika token sudah kedaluwarsa (lebih dari 60 menit)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['success' => false, 'message' => 'Token reset password sudah kedaluwarsa.'], 400);
        }

        // Update password user
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token setelah berhasil digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password Anda berhasil direset.'
        ]);
    }
    
    /**
     * Memperbarui FCM token untuk pengguna yang sedang login.
     */
    public function updateFcmToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $user = $request->user();
        $user->update([
            'fcm_token' => $request->fcm_token,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'FCM token updated successfully.'
        ]);
    }
    
}