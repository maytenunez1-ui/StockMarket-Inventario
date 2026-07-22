<?php

use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\PublisherController as AdminPublisherController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalogo/{book:slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/registro', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/registro', [RegisteredUserController::class, 'store']);
    Route::get('/iniciar-sesion', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/iniciar-sesion', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/cerrar-sesion', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/mis-compras', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/catalogo/{book:slug}/compra', [LoanController::class, 'store'])->name('loans.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('books', AdminBookController::class)->except(['show']);
    Route::resource('authors', AdminAuthorController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('publishers', AdminPublisherController::class)->except(['show']);
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::get('/loans', [AdminLoanController::class, 'index'])->name('loans.index');
    Route::patch('/loans/{loan}', [AdminLoanController::class, 'update'])->name('loans.update');
});
