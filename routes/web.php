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

Route::get('/', function () {
    return view('welcome');
});

Route::get('application_export_excel', [\App\Http\Controllers\ApplicationExport::class, 'exportToExcel'])->name('application_export_excel');
Route::get('user_export_excel', [\App\Http\Controllers\UsersExport::class, 'exportToExcel'])->name('user_export_excel');
