<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PadiAnalysisController;

/*
|--------------------------------------------------------------------------
| PadiGuard AI — Web Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/scan', [PadiAnalysisController::class, 'index'])->name('scan');
Route::post('/analyze', [PadiAnalysisController::class, 'analyze'])->name('analyze');
Route::get('/download-pdf', [PadiAnalysisController::class, 'downloadPdf'])->name('download.pdf');
