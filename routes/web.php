<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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
    return redirect()->route('contacts.index');
});

// Route::resource('contacts', ContactController::class);

Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::post('contacts/save', [ContactController::class, 'saveData'])->name('contacts.save');
Route::get('contacts/form/{id?}', [ContactController::class, 'form'])->name('contacts.form');
Route::post('contacts/import', [ContactController::class, 'importXml'])->name('contacts.import');
Route::delete('contacts/delete/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');