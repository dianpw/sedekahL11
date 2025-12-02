<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route untuk autentikasi (login, logout, lupa password, reset password)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Route untuk pendaftaran (bisa diakses sebelum login)
Route::get('/register', [RegistrationController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [RegistrationController::class, 'register'])->name('register');

// API untuk dropdown alamat dinamis
Route::get('/api/regencies/{provinceId}', [RegistrationController::class, 'getRegencies'])->name('api.regencies');
Route::get('/api/districts/{regencyId}', [RegistrationController::class, 'getDistricts'])->name('api.districts');
Route::get('/api/villages/{districtId}', [RegistrationController::class, 'getVillages'])->name('api.villages');

// Route untuk member (memerlukan auth dan role member)
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
    Route::post('/withdraw', [MemberController::class, 'requestWithdraw'])->name('withdraw');
    // Tambahkan route lain untuk member di sini (akses produk, profil, dll)
});

// Route untuk admin (memerlukan auth dan role admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/marketing-pins', [AdminController::class, 'manageMarketingPins'])->name('marketing-pins');
    Route::post('/marketing-pins', [AdminController::class, 'createMarketingPin'])->name('marketing-pins.create');
    Route::get('/withdrawals', [AdminController::class, 'manageWithdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/confirm', [AdminController::class, 'confirmWithdrawal'])->name('withdrawals.confirm');
    // Tambahkan route lain untuk admin di sini (manajemen produk, laporan, dll)
});
