<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminUtilisateurController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('index');
})->name('index')->middleware(['check.user.or.guest']);


//  **       Sign
Route::middleware(['guest', 'check.user.or.guest'])->controller(AuthController::class)->group(function () {

    // ----------- Sign In -----------
    Route::get('/signin', 'indexin')->name('signin');
    Route::post('/signin', 'auth')->name('login');
});

// ----------- Logout (for logged-in users only) -----------
Route::middleware(['auth', 'role:user'])->get('/logout', [AuthController::class, 'logout'])->name('logout');





Route::middleware(['auth', 'role:user'])
    ->prefix('features')
    ->controller(InvoiceController::class)
    ->group(function () {

        // 🧾 List all invoices
        Route::get('/', 'index')->name('invoice.index');

        // 👁️ Show one invoice
        Route::get('/show/{id}', 'show')->name('invoice.show');


        // ➕ Create new invoice (form)
        Route::get('/create', 'create')->name('invoice.create');

        // 📤 Upload + OCR parse
        Route::post('/store', 'store')->name('invoice.store');

        // 💾 Confirm & save to database
        Route::post('/confirm', 'confirm')->name('invoice.confirm');

        // ✏️ Edit existing invoice
        Route::get('/edit/{id}', 'edit')->name('invoice.edit');

        // 🔄 Update existing invoice
        Route::put('/update/{id}', 'update')->name('invoice.update');
        Route::delete('/{invoice}',  'destroy')->name('invoice.destroy');
    });



// === Admin Section (protected) ===
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    // Invoice routes 
    Route::resource('invoices', AdminInvoiceController::class);
    Route::post('/invoices/confirm', [AdminInvoiceController::class, 'confirm'])->name('invoices.confirm');
    Route::post('/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('invoices.show');


    // Extra routes for approve / reject
    Route::post('/invoices/{id}/approve', [AdminInvoiceController::class, 'approve'])->name('invoices.approve');
    Route::post('/invoices/{id}/reject', [AdminInvoiceController::class, 'reject'])->name('invoices.reject');

    // utilisateur routes
    Route::resource('/users', AdminUtilisateurController::class)->only(['index', 'show']);
});




// // Super Admin routes
Route::middleware('role:super_admin')->prefix('super-admin')->name('super_admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    // Invoice routes
    Route::resource('invoices', AdminInvoiceController::class);
    Route::post('/invoices/confirm', [AdminInvoiceController::class, 'confirm'])->name('invoices.confirm');
    Route::post('/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('invoices.show');

    // Extra routes for approve / reject
    Route::post('/invoices/{id}/approve', [AdminInvoiceController::class, 'approve'])->name('invoices.approve');
    Route::post('/invoices/{id}/reject', [AdminInvoiceController::class, 'reject'])->name('invoices.reject');

    // utilisateur routes
    Route::resource('/users', AdminUtilisateurController::class);


    // profile routes
    Route::get('/profile/edit', [AdminController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminController::class, 'update'])->name('profile.update');
    Route::get('/profile', [AdminController::class, 'show'])->name('profile.show');
});



Route::get('/adminqwer/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
Route::post('/adminqwer/login', [AdminAuthController::class, 'login'])->name('admin.login.post')->middleware('guest');
// Route::get('/adminqwer/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::post('/adminqwer/logout', [AdminAuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

Route::get('/adminqwer/password/reset', [AdminAuthController::class, 'showLinkRequestForm'])
    ->name('admin.password.request')->middleware('auth');
Route::post('/adminqwer/password/reset', [AdminAuthController::class, 'update'])
    ->name('admin.password.update')->middleware('auth');
