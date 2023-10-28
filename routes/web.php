<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackinglistpdfController;


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
Route::get('{record}/pdf',[HomeController::class,'index'])->name('barcode.pdf.download');
Route::get('{record}/barcode',[HomeController::class,'generate'])->name('barcode1.pdf.download');
// Route::get('{record}/pdf',[PackinglistpdfController::class,'index'])->name('packlistdownload');