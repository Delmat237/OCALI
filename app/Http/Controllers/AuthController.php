<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserBook;
use App\Models\Wallet;
use App\Services\EmailService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        // Removed BackendAuthService dependency
    }

    /**
     * Show setup form
     */
    public function setup()
    {
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('login');
        }
        return view('auth.setup');
    }

    /**
     * Store setup
     */
    public function storeSetup(Request $request)
    {
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $user->update(['last_login_at' => now()]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => __('messages.invalid_credentials'),
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{8,14}$/'], 
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:reader,author'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'locale' => app()->getLocale(),
            'is_active' => true,
        ]);

        // Create wallet for authors
        if ($user->isAuthor()) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        }

        $this->addWelcomeBook($user);

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            Log::error('Email verification notification failed: ' . $e->getMessage());
        }

        try {
            $emailService = new EmailService();
            $emailService->sendWelcomeEmail($user);
        } catch (\Exception $e) {
            Log::error('Welcome email failed: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect(route('dashboard'))->with('success', __('messages.registration_success'));
    }

    /**
     * Redirect to Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google Callback
     */
    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            return $this->handleSocialUser($socialUser);
        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', __('messages.social_login_failed'));
        }
    }

    /**
     * Redirect to Facebook
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook Callback
     */
    public function handleFacebookCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            return $this->handleSocialUser($socialUser);
        } catch (\Exception $e) {
            Log::error('Facebook Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', __('messages.social_login_failed'));
        }
    }

    /**
     * Common handler for social users
     */
    private function handleSocialUser($socialUser)
    {
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            Auth::login($user, true);
            $user->update(['last_login_at' => now()]);
            return redirect()->route('dashboard');
        }

        // New user from social: Save in session and redirect to complete profile
        session(['social_pending' => [
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'avatar' => $socialUser->getAvatar(),
        ]]);

        return redirect()->route('auth.complete-profile');
    }

    /**
     * Handle Social Callback with Local User (Bridge for legacy URLs)
     */
    public function handleSocialCallback(Request $request)
    {
        return redirect()->route('login')->with('info', 'Veuillez utiliser les boutons de connexion sociale directs.');
    }

    /**
     * Show complete profile form
     */
    public function showCompleteProfile()
    {
        $pending = session('social_pending');

        if (!$pending) {
            return redirect()->route('login')->with('error', 'Session expirée. Veuillez vous reconnecter.');
        }

        return view('auth.complete-profile', compact('pending'));
    }

    /**
     * Save completed social profile
     */
    public function saveCompleteProfile(Request $request)
    {
        $pending = session('social_pending');
        if (!$pending) {
            return redirect(route('register'));
        }

        $request->validate([
            'role'  => ['required', 'in:reader,author'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{8,14}$/'],
        ]);
        
        // Final check to prevent duplicate email error
        if (User::where('email', $pending['email'])->exists()) {
            session()->forget('social_pending');
            return redirect()->route('login')->with('error', __('messages.email_already_registered'));
        }

        // Create local user
        $user = User::create([
            'name'              => $pending['name'],
            'email'             => $pending['email'],
            'password'          => Hash::make(Str::random(24)),
            'role'              => $request->role,
            'phone'             => $request->phone,
            'email_verified_at' => now(),
            'avatar'            => $pending['avatar'],
            'locale'            => app()->getLocale(),
            'is_active'         => true,
        ]);

        $this->addWelcomeBook($user);
        try {
            (new EmailService())->sendWelcomeEmail($user);
        } catch (\Exception $e) {}

        session()->forget('social_pending');

        Auth::login($user, true);
        $user->update(['last_login_at' => now()]);

        return redirect(route('dashboard'));
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show reset password form
     */
    public function showResetPassword(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Email verification notice
     */
    public function verifyNotice()
    {
        return view('auth.verify-email');
    }

    /**
     * Verify email
     */
    public function verifyEmail(Request $request)
    {
        $request->user()->markEmailAsVerified();

        return redirect(route('dashboard'))->with('success', __('messages.email_verified'));
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Verification email failed: ' . $e->getMessage());
            return back()->with('error', __('messages.verification_email_failed'));
        }

        return back()->with('status', __('messages.verification_link_sent'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Add welcome book to user library
     */
    private function addWelcomeBook(User $user)
    {
        try {
            $welcomeBookId = Setting::getValue('welcome_book_id', null, 'integer', 'books');
            $book = null;

            if ($welcomeBookId) {
                $book = Book::find($welcomeBookId);
            }

            if (!$book) {
                $book = Book::where('is_free_welcome_book', true)->first();
            }

            if ($book) {
                UserBook::firstOrCreate([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                ], [
                    'added_at' => now(),
                    'status' => 'active',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to add welcome book: ' . $e->getMessage());
        }
    }
}
