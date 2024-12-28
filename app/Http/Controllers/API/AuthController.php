<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisterMail;
use App\Mail\GenerateEmailMail;
use App\Models\OtpCode;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,id',
            'password' => 'required|min:8|confirmed',
        ], [
            'required' => "inputan :attribute harus diisi",
            'min' => "inputan :attribute minimal :min karakter",
            'email' => "inputan email harus berformat email",
            'unique' => "email sudah terdaftar",
            'confirmed' => "password tidak sesuai"
        ]);

        $user = new User;
        $roleUser = Roles::where('name', 'user')->first();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;

        $user->save();

        Mail::to($user->email)->send(new UserRegisterMail($user));


        return response([
            "message" => "User berhasil diregister berhasil, silahkan check email",
            "user" => $user

        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'required' => "Inputan :attribute harus diisi",
        ]);
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'User Invalid'], 401);
        };

        $dataUser = User::with('role')->where('email', $request->input('email'))->first();

        return response([
            "message" => "User berhasil login",
            "user" => $dataUser,
            "token" => $token

        ], 200);
    }

    public function currentUser()
    {
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login
        $user = User::with('role')->find($userId);
        return response()->json([
            'message' => 'profile berhasil ditampilkan',
            'user' => $user
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response([
            'message' => 'Logout berhasil'
        ], 200);
    }

    public function generateOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'required' => "Inputan :attribute harus diisi",

        ]);

        $user = User::where('email', $request->input('email'))->first();
        $user->generate_otp();
        Mail::to($user->email)->send(new GenerateEmailMail($user));


        return response()->json([
            'message' => 'otp code berhasil digenerate',
        ]);
    }

    public function verifikasi(Request $request)
    {
        $request->validate([
            'otp' => 'required|min:6',
        ], [
            'required' => "Inputan :attribute harus diisi",
            'min' => "Inputan minimal :min karakter"
        ]);

        $userId = auth()->id();

        // Cari OTP berdasarkan inputan dan user ID
        $otp_code = OtpCode::where('otp', $request->input('otp'))
            ->where('user_id', $userId)
            ->first();

        // Jika OTP tidak ditemukan
        if (!$otp_code) {
            return response([
                'message' => "OTP Code tidak ditemukan"
            ], 404);
        }

        // Periksa validitas OTP
        $now = Carbon::now();
        if ($now > $otp_code->valid_until) {
            return response([
                'message' => "OTP Code sudah tidak berlaku, silahkan generate ulang"
            ], 400);
        }

        // Update kolom email_verified_at pada user
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = $now;

        if ($user->save()) {
            // Hapus OTP setelah verifikasi berhasil
            $otp_code->delete();

            return response([
                'message' => "Verifikasi berhasil"
            ], 200);
        } else {
            return response([
                'message' => "Gagal menyimpan perubahan pada user"
            ], 500);
        }
    }
}
