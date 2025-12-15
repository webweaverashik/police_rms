<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\PoliticalPartyController;
use App\Http\Controllers\ProgramTypeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth', 'isLoggedIn'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.admin');
    })->name('dashboard');

    // Only allow POST method for actual logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Handle GET /logout: Redirect back if logged in
    Route::get('/logout', function () {
        return redirect()->back();
    })->name('logout.get');

    // ------- Custom routes start -------
    Route::resource('users', UserController::class);
    Route::resource('designations', DesignationController::class);
    Route::resource('political-parties', PoliticalPartyController::class);
    Route::resource('program-types', ProgramTypeController::class);
    Route::resource('reports', ReportController::class);
    // ------- Custom routes end -------
});

// Handle GET /logout for logged-out users (redirect to login)
Route::get('/logout', function () {
    return redirect()->route('login');
})->name('logout.get');

Route::controller(PasswordController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('forgot-password', 'showLinkRequestForm')->name('password.request');
        Route::post('forgot-password', 'sendResetLinkEmail')->name('password.email');
        Route::get('reset-password', function () {
            return redirect()->route('password.request');
        })->name('password.reset.request');
        Route::get('reset-password/{token}', 'showResetForm')->name('password.reset');
        Route::post('reset-password', 'reset')->name('password.update');
    });

// ----- Tools for testing ----
// Testing mail server
Route::get('/send-test-email', function () {
    Mail::raw('This is a test email from Laravel 12!', function ($message) {
        $message->to('ashik.ane.doict@gmail.com')->subject('Laravel 12 Gmail SMTP Test');
    });

    return 'Test email sent successfully!';
});

Route::get('/run-command/{slug}', function (string $slug) {
    // ❌ Block completely in production
    abort_if(app()->environment('production'), 404);

    // 🔐 Must be logged in
    abort_unless(auth()->check(), 403);

    // 🔐 Must be Administrator
    abort_unless(auth()->user()->role && auth()->user()->role->name === 'Administrator', 403);

    // ✅ Allowed slug → command mapping
    $commands = [
        'optimize-clear'       => 'optimize:clear',
        'migrate'              => 'migrate',
        'migrate-seed'         => 'migrate --seed',
        'migrate-refresh'      => 'migrate:refresh',
        'migrate-refresh-seed' => 'migrate:refresh --seed',
    ];

    abort_unless(array_key_exists($slug, $commands), 404);

    // ▶ Run command
    Artisan::call($commands[$slug]);

    return response()->json([
        'success' => true,
        'slug'    => $slug,
        'command' => $commands[$slug],
        'output'  => Artisan::output(),
    ]);
})->name('run.command');
