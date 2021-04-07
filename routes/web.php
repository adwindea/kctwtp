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
Route::post('logged_in', [App\Http\Controllers\Auth\LoginController::class, 'authenticate']);

Route::get('/', [App\Http\Controllers\EndUserController::class, 'index'])->name('idForm');
Route::post('/detailPel', [App\Http\Controllers\EndUserController::class, 'detailPel'])->name('detailPel');
Route::get('/updateToken/{id}', [App\Http\Controllers\EndUserController::class, 'updateToken'])->name('updateToken');
Route::post('/kctStatus', [App\Http\Controllers\EndUserController::class, 'kctStatus'])->name('kctStatus');
Route::post('/submitUpgrade', [App\Http\Controllers\EndUserController::class, 'submitUpgrade'])->name('submitUpgrade');
Route::get('/thanksPage', [App\Http\Controllers\EndUserController::class, 'thanksPage'])->name('thanksPage');


Route::get('/dashboard', [App\Http\Controllers\OfficeController::class, 'dataPelanggan'])->name('dashboard');
Route::get('/dataPelanggan', [App\Http\Controllers\OfficeController::class, 'dataPelanggan'])->name('dataPelanggan');
Route::post('/dataPelangganTable', [App\Http\Controllers\OfficeController::class, 'dataPelangganTable'])->name('dataPelangganTable');
Route::post('/confirmUpgrade', [App\Http\Controllers\OfficeController::class, 'confirmUpgrade'])->name('confirmUpgrade');
Route::get('/importPage', [App\Http\Controllers\OfficeController::class, 'importPage'])->name('importPage')->middleware('admin');
Route::post('/importPelanggan', [App\Http\Controllers\OfficeController::class, 'importPelanggan'])->name('importPelanggan')->middleware('admin');

Route::post('/TSuj5c@k2HRWx@QzUWPMVdmXn5_49Sc!/webhook', [App\Http\Controllers\BotHandlerController::class, 'telegramHandler'])->name('telegramHandler');
Route::get('/TSuj5c@k2HRWx@QzUWPMVdmXn5_49Sc!/webhook', [App\Http\Controllers\BotHandlerController::class, 'telegramHandler'])->name('telegramHandler');
Route::post('/getupdate', [App\Http\Controllers\BotHandlerController::class, 'telegramHandler2'])->name('telegramHandler2');
Route::get('/getupdate', [App\Http\Controllers\BotHandlerController::class, 'telegramHandler2'])->name('telegramHandler2');
Route::get('/unsetWebhook', [App\Http\Controllers\BotHandlerController::class, 'unsetWebhook'])->name('unsetWebhook');
Route::post('/resetPending', [App\Http\Controllers\BotHandlerController::class, 'resetPending'])->name('resetPending');
Route::get('/resetPending', [App\Http\Controllers\BotHandlerController::class, 'resetPending'])->name('resetPending');


Route::get('/unAuth', [App\Http\Controllers\HomeController::class, 'unauthorizedAccess'])->name('unAuth');
Route::get('/userList', [App\Http\Controllers\HomeController::class, 'userList'])->name('userList')->middleware('admin');
Route::post('/userListTable', [App\Http\Controllers\HomeController::class, 'userListTable'])->name('userListTable')->middleware('admin');
Route::post('/userActivation', [App\Http\Controllers\HomeController::class, 'userActivation'])->name('userActivation')->middleware('admin');
