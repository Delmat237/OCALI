<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChronicleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Setup (only when users table is empty)
Route::get('/setup', [AuthController::class, 'setup'])->name('setup');
Route::post('/setup', [AuthController::class, 'storeSetup'])->name('setup.store');

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explore', [HomeController::class, 'explore'])->name('explore');
Route::get('/categories', [HomeController::class, 'categories'])->name('categories');
Route::get('/categories/{category:slug}', [HomeController::class, 'category'])->name('category.show');

// Locale & Theme
Route::post('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');

// Static Pages
Route::view('/publishing-guide', 'pages.publishing-guide')->name('publishing-guide');
Route::view('/royalties', 'pages.royalties')->name('royalties');
Route::view('/help', 'pages.help')->name('help');
Route::get('/contact', [HomeController::class, 'showContact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');

// Books (Public)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');

// Chronicles (Public)
Route::get('/chronicles', [ChronicleController::class, 'index'])->name('chronicles.index');
Route::get('/chronicles/{chronicle:slug}', [ChronicleController::class, 'show'])->name('chronicles.show');

// Subscription Plans
Route::get('/pricing', [SubscriptionController::class, 'plans'])->name('pricing');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Social Login
    // Social Login (Redirects to backend are handled in logic/views)
    Route::get('/auth/social-callback', [AuthController::class, 'handleSocialCallback'])->name('auth.social.callback');
    Route::get('/auth/complete-profile', [AuthController::class, 'showCompleteProfile'])->name('auth.complete-profile');
    Route::post('/auth/complete-profile', [AuthController::class, 'saveCompleteProfile'])->name('auth.complete-profile.save');

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);

    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Email Verification
Route::get('/email/verify', [AuthController::class, 'verifyNotice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [DashboardController::class, 'deleteAccount'])->name('profile.delete');

    // My Library
    Route::get('/library', [ReaderController::class, 'library'])->name('library');
    Route::get('/library/history', [ReaderController::class, 'history'])->name('library.history');

    // Book Reading & Downloading
    Route::get('/books/{book}/read', [ReaderController::class, 'read'])->name('books.read');

    Route::post('/books/{book}/progress', [ReaderController::class, 'updateProgress'])->name('books.progress');
    Route::post('/read/{book}/bookmark', [ReaderController::class, 'addBookmark'])->name('books.bookmark');

    // Book Reports
    Route::post('/books/{book}/report', [BookController::class, 'report'])->name('books.report');

    // Reviews
    Route::post('/books/{book}/review', [BookController::class, 'storeReview'])->name('books.review');
    Route::delete('/reviews/{review}', [BookController::class, 'destroyReview'])->name('reviews.destroy');

    // Subscriptions
    Route::get('/subscribe/{slug}', [SubscriptionController::class, 'show'])->name('subscribe.show');
    Route::post('/subscribe/{slug}', [SubscriptionController::class, 'subscribe'])->name('subscribe.process');
    Route::get('/subscription/payment/{subscription}', [SubscriptionController::class, 'payment'])->name('subscription.payment');
    Route::post('/subscription/payment/{subscription}/backend-paymooney', [SubscriptionController::class, 'processBackendPayment'])->name('subscription.backend-paymooney');
    Route::post('/subscription/payment/{subscription}/paymooney', [SubscriptionController::class, 'createPaymooneyLink'])->name('subscription.paymooney');

    // Notifications
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [DashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [DashboardController::class, 'markAllNotificationsRead'])->name('notifications.read-all');

    /*
    |--------------------------------------------------------------------------
    | Author Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('author')->prefix('author')->name('author.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'authorDashboard'])->name('dashboard');

        // Book Management
        Route::get('/books', [BookController::class, 'myBooks'])->name('books.index');
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        // Statistics
        Route::get('/statistics', [DashboardController::class, 'authorStatistics'])->name('statistics');

        // Wallet
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
        Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');
        Route::get('/wallet/withdraw', [WalletController::class, 'withdrawForm'])->name('wallet.withdraw');
        Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw.process');

        // Chronicles
        Route::get('/chronicles', [ChronicleController::class, 'myChronicles'])->name('chronicles.index');
        Route::get('/chronicles/create', [ChronicleController::class, 'create'])->name('chronicles.create');
        Route::post('/chronicles', [ChronicleController::class, 'store'])->name('chronicles.store');
        Route::get('/chronicles/{chronicle}/edit', [ChronicleController::class, 'edit'])->name('chronicles.edit');
        Route::put('/chronicles/{chronicle}', [ChronicleController::class, 'update'])->name('chronicles.update');
        Route::delete('/chronicles/{chronicle}', [ChronicleController::class, 'destroy'])->name('chronicles.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');

        // Books 
        Route::get('/books', [AdminController::class, 'books'])->name('books.index');
        Route::get('/books/pending', [AdminController::class, 'pendingBooks'])->name('books.pending');
        Route::get('/books/{book}/preview', [AdminController::class, 'previewBook'])->name('books.preview');
        Route::post('/books/{book}/approve', [AdminController::class, 'approveBook'])->name('books.approve');
        Route::post('/books/{book}/reject', [AdminController::class, 'rejectBook'])->name('books.reject');
        Route::put('/books/{book}/welcome', [AdminController::class, 'setWelcomeBook'])->name('books.welcome');

        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
        Route::get('/reports/{report}', [AdminController::class, 'showReport'])->name('reports.show');
        Route::put('/reports/{report}', [AdminController::class, 'handleReport'])->name('reports.handle');

        // Subscriptions
        Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions.index');

        // Plans
        Route::get('/plans', [AdminController::class, 'plans'])->name('plans.index');
        Route::get('/plans/create', [AdminController::class, 'createPlan'])->name('plans.create');
        Route::post('/plans', [AdminController::class, 'storePlan'])->name('plans.store');
        Route::get('/plans/{id}/edit', [AdminController::class, 'editPlan'])->name('plans.edit');
        Route::put('/plans/{id}', [AdminController::class, 'updatePlan'])->name('plans.update');
        Route::delete('/plans/{id}', [AdminController::class, 'deletePlan'])->name('plans.destroy');

        // Withdrawals
        Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals.index');
        Route::post('/withdrawals/{withdrawal}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');

        // Categories
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Newsletter
        Route::get('/newsletter', [AdminController::class, 'newsletter'])->name('newsletter.index');
        Route::post('/newsletter', [AdminController::class, 'sendNewsletter'])->name('newsletter.send');

        // Statistics & Reports
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
        Route::get('/financial-reports', [AdminController::class, 'financialReports'])->name('financial-reports');
    });
    
});
// Met dans le dashbord de l'admin, de l'auteur et des lecteurs 
// les bouton pour effectuer toutes les actions pour chaque utilisateur, 
// respectivement

/*
|--------------------------------------------------------------------------
| Payment Callbacks
|--------------------------------------------------------------------------
*/

Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/nokash/callback', [PaymentCallbackController::class, 'nokashCallback'])->name('nokash.callback');
    Route::post('/nokash/success/{subscription}', [PaymentCallbackController::class, 'nokashSuccess'])->name('nokash.success');
    Route::post('/paymooney/callback', [PaymentCallbackController::class, 'paymooneyCallback'])->name('paymooney.callback');
    Route::get('/paymooney/success', [PaymentCallbackController::class, 'paymooneySuccess'])->name('paymooney.success');
    Route::get('/paymooney/fail', [PaymentCallbackController::class, 'paymooneyFail'])->name('paymooney.fail');
});

// Generic Payment Result Pages (outside prefix to have clean route names)
Route::view('/payment/success', 'payment.success')->name('checkout.success');
Route::view('/payment/fail', 'payment.fail')->name('checkout.fail');

/*
|--------------------------------------------------------------------------
| Newsletter
|--------------------------------------------------------------------------
*/

Route::post('/newsletter/subscribe', [HomeController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [HomeController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');
