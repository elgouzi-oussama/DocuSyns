<?php

use App\Helpers\SystemHelper;
use App\Http\Controllers\PermissionsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Admin\AdminUtilisateurController;
use Spatie\Permission\Contracts\Permission;

Route::middleware(['locale', 'license.check'])->group(function () {
    // Language switcher route
    Route::get('/set-locale/{locale}', [LocaleController::class, 'setLocale'])->name('set-locale');

    Route::get('/', [UserController::class, 'index'])->name('index')->middleware(['check.user.or.guest']);
    // Other public routes

    Route::get('/rapports', [RapportController::class, 'index'])->name('user.rapports.index');
    Route::get('/contact', [ContactController::class, 'index'])->name('user.contact.index');
    Route::post('/contact', [ContactController::class, 'send'])->name('user.contact.send');


    //  **       Sign
    Route::middleware(['guest', 'check.user.or.guest'])->controller(AuthController::class)->group(function () {

        // ----------- Sign In -----------
        Route::get('/signin', 'index')->name('signin');
        Route::post('/signin', 'auth')->name('login');
    });

    // ----------- Logout (for logged-in users only) -----------
    Route::middleware(['auth', 'role:user'])->get('/logout', [AuthController::class, 'logout'])->name('logout');


    // === Profile Section (protected) === Admin role
    Route::prefix('profile')
        ->controller(UserController::class)
        ->middleware(['auth', 'role:user'])
        ->group(function () {
            Route::get('/edit',  'edit')->name('profile.edit')->middleware('permission:profile.edit');
            Route::put('/',  'update')->name('profile.update')->middleware('permission:profile.edit');
            Route::get('/',  'show')->name('profile.show')->middleware('permission:profile.show');
        });

    // === Invoice Routes (protected) === USER ROLE
    Route::middleware(['auth', 'role:user'])
        ->prefix('invoices')
        ->controller(InvoiceController::class)
        ->group(function () {

            // 🧾 List all invoices
            Route::get('/', 'index')->name('invoice.index')->middleware('permission:invoice.index');

            // 👁️ Show one invoice
            Route::get('/show/{id}', 'show')->name('invoice.show')->middleware('permission:invoice.show');


            // ➕ Create new invoice (form)
            Route::get('/create', 'create')->name('invoice.create')->middleware('permission:invoice.create');

            // 📤 Upload + OCR parse
            Route::post('/store', 'store')->name('invoice.store')->middleware('permission:invoice.create');

            // 💾 Confirm & save to database
            Route::post('/confirm', 'confirm')->name('invoice.confirm')->middleware('permission:invoice.create');

            // ✏️ Edit existing invoice
            Route::get('/edit/{id}', 'edit')->name('invoice.edit')->middleware('permission:invoice.edit');

            // 🔄 Update existing invoice
            Route::put('/update/{id}', 'update')->name('invoice.update')->middleware('permission:invoice.edit');
            Route::delete('/{invoice}',  'destroy')->name('invoice.destroy')->middleware('permission:invoice.delete');
        });



    // === Admin Section (protected) === and 'admin' role
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

            Route::get('/', [AdminController::class, 'index'])->name('dashboard');
            // Invoice routes 
            Route::resource('invoices', AdminInvoiceController::class);
            Route::post('/invoices/confirm', [AdminInvoiceController::class, 'confirm'])->name('invoices.confirm');
            Route::post('/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('invoices.show');


            // Extra routes for approve / reject
            Route::post('/invoices/{id}/approve', [AdminInvoiceController::class, 'approve'])->name('invoices.approve');
            Route::post('/invoices/{id}/reject', [AdminInvoiceController::class, 'reject'])->name('invoices.reject');

            // utilisateur routes
            Route::get('/users/permissions', [AdminController::class, 'permissions'])->name('users.permissions.index');
            Route::put('/users/permissions/{user}', [AdminUtilisateurController::class, 'permissions'])->name('users.permissions');

            Route::resource('/users', AdminUtilisateurController::class);


            // profile routes
            Route::get('/profile/edit', [AdminController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [AdminController::class, 'update'])->name('profile.update');
            Route::get('/profile', [AdminController::class, 'show'])->name('profile.show');
        });




    // // Super Admin routes (protected) and 'super_admin' role
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
        Route::get('/users/permissions', [PermissionsController::class, 'index'])->name('users.permissions.index');
        Route::put('/users/permissions/{user}', [PermissionsController::class, 'update'])->name('users.permissions');
        Route::resource('/users', AdminUtilisateurController::class);


        // profile routes
        Route::get('/profile/edit', [AdminController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminController::class, 'update'])->name('profile.update');
        Route::get('/profile', [AdminController::class, 'show'])->name('profile.show');


        // License management routes
        Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
        Route::post('/licenses/upgrade', [LicenseController::class, 'upgrade'])->name('licenses.upgrade');
    });


    // Admin Authentication Routes (grouped by controller + middleware)
    Route::controller(AdminAuthController::class)
        ->group(function () {

            // Guest-only (login)
            Route::middleware('guest')->group(function () {
                Route::get('/adminqwer/login', 'showLoginForm')->name('admin.login');
                Route::post('/adminqwer/login', 'login')->name('admin.login.post');
            });

            // Authenticated (logout, password reset)
            Route::middleware('auth')->group(function () {
                Route::post('/adminqwer/logout', 'logout')->name('admin.logout');
                Route::get('/adminqwer/password/reset', 'showLinkRequestForm')->name('admin.password.request');
                Route::post('/adminqwer/password/reset', 'update')->name('admin.password.update');
            });
        });

    // Clear cache route
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        return "Cache is cleared";
    });



    // License management routes
    Route::get('super-admin/licenses', [LicenseController::class, 'index'])
        ->name('super_admin.licenses.index');
});
