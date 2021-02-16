<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\EndUserController::class, 'index'])->name('idForm');
Route::post('/detailPel', [App\Http\Controllers\EndUserController::class, 'detailPel'])->name('detailPel');
Route::get('/updateToken/{id}', [App\Http\Controllers\EndUserController::class, 'updateToken'])->name('updateToken');
Route::post('/kctStatus', [App\Http\Controllers\EndUserController::class, 'kctStatus'])->name('kctStatus');
Route::post('/submitUpgrade', [App\Http\Controllers\EndUserController::class, 'submitUpgrade'])->name('submitUpgrade');
Route::get('/thanksPage', [App\Http\Controllers\EndUserController::class, 'thanksPage'])->name('thanksPage');


Route::get('/dashboard', [App\Http\Controllers\OfficeController::class, 'dashboard'])->name('dashboard');
Route::get('/dataPelanggan', [App\Http\Controllers\OfficeController::class, 'dataPelanggan'])->name('dataPelanggan');
Route::post('/dataPelangganTable', [App\Http\Controllers\OfficeController::class, 'dataPelangganTable'])->name('dataPelangganTable');
Route::post('/confirmUpgrade', [App\Http\Controllers\OfficeController::class, 'confirmUpgrade'])->name('confirmUpgrade');

Route::post('/initokenwebhook1/webhook', [App\Http\Controllers\BotHandlerController::class, 'telegramHandler'])->name('telegramHandler');
Route::get('/initokenwebhook1/webhook', [App\Http\Controllers\BotHandlerController::class, 'telegramHandler'])->name('telegramHandler');
