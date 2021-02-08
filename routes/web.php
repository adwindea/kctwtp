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

Route::get('/dashboard', [App\Http\Controllers\OfficeController::class, 'dashboard'])->name('dashboard');
Route::get('/dataPelanggan', [App\Http\Controllers\OfficeController::class, 'dataPelanggan'])->name('dataPelanggan');
