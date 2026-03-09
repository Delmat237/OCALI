<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API endpoints
Route::prefix('v1')->group(function () {
    // Auth
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);

    // Books
    Route::get('/books', [\App\Http\Controllers\Api\BookController::class, 'index']);
    Route::get('/books/featured', [\App\Http\Controllers\Api\BookController::class, 'featured']);
    Route::get('/books/latest', [\App\Http\Controllers\Api\BookController::class, 'latest']);
    Route::get('/books/{book:slug}', [\App\Http\Controllers\Api\BookController::class, 'show']);

    // Categories
    Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);

    // Chronicles
    Route::get('/chronicles', [\App\Http\Controllers\Api\ChronicleController::class, 'index']);
    Route::get('/chronicles/{chronicle:slug}', [\App\Http\Controllers\Api\ChronicleController::class, 'show']);
    // Forgot Password
    Route::post('/forgot-password', [\App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
    
    // Email Verification
    Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Api\AuthController::class, 'verifyEmailApi']);

    // Subscription Plans
    Route::get('/plans', [\App\Http\Controllers\Api\SubscriptionController::class, 'plans']);
});

// Authenticated API endpoints
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Auth
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    // Email Verification
    Route::post('/email/resend', [\App\Http\Controllers\Api\AuthController::class, 'resendVerificationEmail']);

    // User
    Route::get('/me', [\App\Http\Controllers\Api\UserController::class, 'me']);
    Route::put('/me', [\App\Http\Controllers\Api\UserController::class, 'update']);

    // Library
    Route::get('/library', [\App\Http\Controllers\Api\LibraryController::class, 'index']);
    Route::get('/library/accessible', [\App\Http\Controllers\Api\LibraryController::class, 'getAccessibleBooks']);
    Route::post('/library/{book}', [\App\Http\Controllers\Api\LibraryController::class, 'addBook']);
    Route::delete('/library/{book}', [\App\Http\Controllers\Api\LibraryController::class, 'removeBook']);

    // Reading Progress
    Route::post('/books/{book}/progress', [\App\Http\Controllers\Api\ReaderController::class, 'updateProgress']);
    Route::get('/library/history', [\App\Http\Controllers\Api\LibraryController::class, 'history']);
    Route::post('/books/{book}/bookmark', [\App\Http\Controllers\Api\ReaderController::class, 'addBookmark']);
    Route::get('/books/{book}/bookmarks', [\App\Http\Controllers\Api\ReaderController::class, 'getBookmarks']);
    
    // Wallet
    Route::get('/wallet', [\App\Http\Controllers\Api\WalletController::class, 'index']);
    
    // Reviews
    Route::get('/books/{book}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);
    Route::post('/books/{book}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);
    
    // Comments
    Route::get('/chronicles/{chronicle}/comments', [\App\Http\Controllers\Api\CommentController::class, 'index']);
    Route::post('/chronicles/{chronicle}/comments', [\App\Http\Controllers\Api\CommentController::class, 'store']);
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    
    // Report
    Route::post('/books/{book}/report', [\App\Http\Controllers\Api\BookController::class, 'report']);
    
    // Author Routes
    Route::middleware('author')->prefix('author')->group(function () {
        // Books
        Route::get('/books', [\App\Http\Controllers\Api\AuthorBookController::class, 'index']);
        Route::post('/books', [\App\Http\Controllers\Api\AuthorBookController::class, 'store']);
        Route::get('/books/{book}', [\App\Http\Controllers\Api\AuthorBookController::class, 'show']);
        Route::put('/books/{book}', [\App\Http\Controllers\Api\AuthorBookController::class, 'update']);
        Route::delete('/books/{book}', [\App\Http\Controllers\Api\AuthorBookController::class, 'destroy']);
        
        // Chronicles
        Route::get('/chronicles', [\App\Http\Controllers\Api\AuthorChronicleController::class, 'index']);
        Route::post('/chronicles', [\App\Http\Controllers\Api\AuthorChronicleController::class, 'store']);
        Route::get('/chronicles/{chronicle}', [\App\Http\Controllers\Api\AuthorChronicleController::class, 'show']);
        Route::put('/chronicles/{chronicle}', [\App\Http\Controllers\Api\AuthorChronicleController::class, 'update']);
        Route::delete('/chronicles/{chronicle}', [\App\Http\Controllers\Api\AuthorChronicleController::class, 'destroy']);
        
        // Withdrawals
        Route::get('/withdrawals', [\App\Http\Controllers\Api\WithdrawalController::class, 'index']);
        Route::post('/withdrawals', [\App\Http\Controllers\Api\WithdrawalController::class, 'store']);
        
        // Statistics
        Route::get('/statistics', [\App\Http\Controllers\Api\AuthorStatisticsController::class, 'index']);
    });
    
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\AdminController::class, 'dashboard']);
        
        // Users
        Route::get('/users', [\App\Http\Controllers\Api\AdminController::class, 'users']);
        Route::get('/users/{user}', [\App\Http\Controllers\Api\AdminController::class, 'showUser']);
        Route::put('/users/{user}', [\App\Http\Controllers\Api\AdminController::class, 'updateUser']);
        Route::delete('/users/{user}', [\App\Http\Controllers\Api\AdminController::class, 'deleteUser']);
        Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Api\AdminController::class, 'toggleUserStatus']);
        
        // Books
        Route::get('/books', [\App\Http\Controllers\Api\AdminController::class, 'books']);
        Route::get('/books/pending', [\App\Http\Controllers\Api\AdminController::class, 'pendingBooks']);
        Route::get('/books/{book}/preview', [\App\Http\Controllers\Api\AdminController::class, 'previewBook']);
        Route::post('/books/{book}/approve', [\App\Http\Controllers\Api\AdminController::class, 'approveBook']);
        Route::post('/books/{book}/reject', [\App\Http\Controllers\Api\AdminController::class, 'rejectBook']);
        Route::put('/books/{book}/welcome', [\App\Http\Controllers\Api\AdminController::class, 'setWelcomeBook']);
        
        // Reports
        Route::get('/reports', [\App\Http\Controllers\Api\AdminController::class, 'reports']);
        Route::get('/reports/{report}', [\App\Http\Controllers\Api\AdminController::class, 'showReport']);
        Route::put('/reports/{report}', [\App\Http\Controllers\Api\AdminController::class, 'handleReport']);
        
        // Subscriptions
        Route::get('/subscriptions', [\App\Http\Controllers\Api\AdminController::class, 'subscriptions']);
        
        // Plans
        Route::get('/plans', [\App\Http\Controllers\Api\AdminController::class, 'plans']);
        Route::post('/plans', [\App\Http\Controllers\Api\AdminController::class, 'storePlan']);
        Route::put('/plans/{plan}', [\App\Http\Controllers\Api\AdminController::class, 'updatePlan']);
        
        // Withdrawals
        Route::get('/withdrawals', [\App\Http\Controllers\Api\AdminController::class, 'withdrawals']);
        Route::post('/withdrawals/{withdrawal}/approve', [\App\Http\Controllers\Api\AdminController::class, 'approveWithdrawal']);
        Route::post('/withdrawals/{withdrawal}/reject', [\App\Http\Controllers\Api\AdminController::class, 'rejectWithdrawal']);
        
        // Categories
        Route::apiResource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        
        // Settings
        Route::get('/settings', [\App\Http\Controllers\Api\AdminController::class, 'settings']);
        Route::put('/settings', [\App\Http\Controllers\Api\AdminController::class, 'updateSettings']);
        
        // Newsletter
        Route::post('/newsletter', [\App\Http\Controllers\Api\AdminController::class, 'sendNewsletter']);
        
        // Statistics
        Route::get('/statistics', [\App\Http\Controllers\Api\AdminController::class, 'statistics']);
        Route::get('/financial-reports', [\App\Http\Controllers\Api\AdminController::class, 'financialReports']);
    });
});
