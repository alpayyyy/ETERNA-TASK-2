<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Rol varsayılan olarak veritabanından 'kullanici' olarak atanacak
        $user = User::create($validated);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Kayıt başarılı',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // E-posta veya telefon olabilir
            'password' => 'required|string',
        ]);

        // Girilen değerin e-posta mı yoksa telefon mu olduğunu kontrol ediyoruz
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($loginField, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Kimlik bilgileri hatalı.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Giriş başarılı',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Çıkış yapıldı'
        ]);
    }
}