<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', function () {
    $todayCandidates = \App\Models\Candidate::whereDate('created_at', today())->count();
    $todayCollection = \App\Models\Payment::whereDate('payment_date', today())->sum('amount');
    $unpaidCount = \App\Models\Invoice::where('status', 'unpaid')->count();

    return view('dashboard', compact('todayCandidates', 'todayCollection', 'unpaidCount'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin,receptionist'])->group(function () {
    Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
    Route::get('/candidates/create', [CandidateController::class, 'create'])->name('candidates.create');
    Route::post('/candidates', [CandidateController::class, 'store'])->name('candidates.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/candidates/{candidate}/edit', [CandidateController::class, 'edit'])->name('candidates.edit');
    Route::put('/candidates/{candidate}', [CandidateController::class, 'update'])->name('candidates.update');
    Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('candidates.destroy');
});

Route::middleware(['auth', 'role:admin,receptionist'])->group(function () {
    Route::get('/candidates/{candidate}/bill', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/candidates/{candidate}/bill', [InvoiceController::class, 'store'])->name('invoices.store');
});

Route::middleware(['auth', 'role:admin,receptionist,accountant'])->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/receipt', [InvoiceController::class, 'receipt'])->name('invoices.receipt');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/test-types', [TestTypeController::class, 'index'])->name('test-types.index');
    Route::get('/test-types/create', [TestTypeController::class, 'create'])->name('test-types.create');
    Route::post('/test-types', [TestTypeController::class, 'store'])->name('test-types.store');
    Route::get('/test-types/{testType}/edit', [TestTypeController::class, 'edit'])->name('test-types.edit');
    Route::put('/test-types/{testType}', [TestTypeController::class, 'update'])->name('test-types.update');
    Route::delete('/test-types/{testType}', [TestTypeController::class, 'destroy'])->name('test-types.destroy');
});

Route::middleware(['auth', 'role:admin,receptionist'])->group(function () {
    Route::get('/invoices/{invoice}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/invoices/{invoice}/pay', [PaymentController::class, 'store'])->name('payments.store');
});

Route::middleware(['auth', 'role:admin,accountant'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});

Route::middleware(['auth', 'role:admin,accountant'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
