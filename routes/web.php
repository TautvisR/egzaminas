<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Hello');
});

Route::get('/dashboard', function () {
    return Inertia::render('Hello');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource("books",\App\Http\Controllers\BookController::class)->only(["index"]);
Route::resource("categories",\App\Http\Controllers\CategoryController::class)->only(["index"]);
Route::post("/books/filter",[\App\Http\Controllers\BookController::class, "filter"])->name("books.filter");


Route::middleware("edit")->group(function (){
    Route::resource("books",\App\Http\Controllers\BookController::class)->except(["index"]);
    Route::resource("categories",\App\Http\Controllers\CategoryController::class)->except(["index"]);
});
require __DIR__.'/auth.php';
