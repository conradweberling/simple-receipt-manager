<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/invitations', [App\Http\Controllers\InvitationController::class, 'index'])->name('invitations');
Route::get('/invitations/create', [App\Http\Controllers\InvitationController::class, 'create'])->name('invitations.create');
Route::post('/invitations', [App\Http\Controllers\InvitationController::class, 'store'])->name('invitations');

Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account');
Route::post('/account/destroy', [App\Http\Controllers\AccountController::class, 'destroy'])->name('account.destroy');

Route::get('/receipts', [App\Http\Controllers\ReceiptController::class, 'index'])->name('receipts');
Route::get('/receipts/create', [App\Http\Controllers\ReceiptController::class, 'create'])->name('receipts.create');
Route::post('/receipts/store', [App\Http\Controllers\ReceiptController::class, 'store'])->name('receipts.store');

Route::get('/images/{name}', [App\Http\Controllers\ImageController::class, 'index'])->name('images');

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

//Route::get('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
//Route::post('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm']);

Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset', [ App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

Route::post('/password/check', [App\Http\Controllers\AccountController::class, 'checkPassword'])->name('password.check');
Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');

Route::get('/register/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'register']);



