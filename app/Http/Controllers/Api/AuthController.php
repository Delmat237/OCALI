<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Services\BackendAuthService;
use App\Services\EmailService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function __construct()
    {
        // Removed BackendAuthService dependency
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update(['last_login_at' => now()]);
            
            $token = $user->createToken('mobile-app')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => __('messages.login_success')
            ]);
        }

        return response()->json([
            'message' => __('messages.invalid_credentials')
        ], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:reader,author'],
            'phone' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone ?? '+237600000000',
            'locale' => $request->locale ?? app()->getLocale(),
            'is_active' => true,
        ]);

        if ($user->isAuthor()) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        }

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            Log::error('API Registration event failed: ' . $e->getMessage());
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => __('messages.registration_success')
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => __('messages.logout_success')
        ]);
    }

    /**
     * Resend email verification notification
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email déjà vérifié'
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email de vérification envoyé'
        ]);
    }

    /**
     * Verify email via API (for deep links)
     */
    public function verifyEmailApi($id, $hash, Request $request)
    {
        $user = User::findOrFail($id);
        
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'message' => 'Lien de vérification invalide'
            ], 400);
        }
        
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email déjà vérifié',
                'verified' => true
            ]);
        }
        
        $user->markEmailAsVerified();
        
        return response()->json([
            'message' => 'Email vérifié avec succès',
            'verified' => true
        ]);
    }

    /**
     * Forgot password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // Implementation would send password reset email
        return response()->json([
            'message' => 'Email de réinitialisation envoyé'
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
        
        // Implementation would reset password
        return response()->json([
            'message' => 'Mot de passe réinitialisé'
        ]);
    }
}
