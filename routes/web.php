<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Billing\BillController;
use App\Http\Controllers\Billing\PaymentController;
use App\Http\Controllers\EnterShopController;
use App\Http\Controllers\Parties\CustomerController;
use App\Http\Controllers\Settings\BusinessProfileController;
use App\Http\Controllers\Settings\BackupController;
use App\Http\Controllers\Settings\MaterialUnitController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('shop/{businessProfile}/enter', [EnterShopController::class, 'enter'])->name('shop.enter');
    Route::post('shop/exit', [EnterShopController::class, 'exit'])->name('shop.exit');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('business-profiles', BusinessProfileController::class);

        // Materials & Units (construction business)
        Route::resource('users', UserController::class);

        // Backup & Restore
        Route::get('backup',                          [BackupController::class, 'index'])->name('backup.index');
        Route::post('backup',                         [BackupController::class, 'store'])->name('backup.store');
        Route::get('backup/download/{filename}',      [BackupController::class, 'download'])->name('backup.download')->where('filename', '.*');
        Route::delete('backup/{filename}',            [BackupController::class, 'destroy'])->name('backup.destroy')->where('filename', '.*');
        Route::post('backup/restore',                 [BackupController::class, 'restore'])->name('backup.restore');

        Route::get('material-units', [MaterialUnitController::class, 'index'])->name('material-units');
        Route::post('materials', [MaterialUnitController::class, 'storeMaterial'])->name('materials.store');
        Route::delete('materials/{material}', [MaterialUnitController::class, 'destroyMaterial'])->name('materials.destroy');
        Route::post('units', [MaterialUnitController::class, 'storeUnit'])->name('units.store');
        Route::delete('units/{unit}', [MaterialUnitController::class, 'destroyUnit'])->name('units.destroy');
    });

    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/assign-business', [CustomerController::class, 'assignBusiness'])->name('customers.assign-business');

    // Purchases (Dairy — buy milk from vendors/farmers)
    Route::get('purchases/vendor-info', [\App\Http\Controllers\Purchases\PurchaseController::class, 'vendorInfo'])->name('purchases.vendor-info');
    Route::get('purchases/{purchase}/pdf', [\App\Http\Controllers\Purchases\PurchaseController::class, 'pdf'])->name('purchases.pdf');
    Route::resource('purchases', \App\Http\Controllers\Purchases\PurchaseController::class)->only(['index', 'create', 'store', 'show']);

    // Billing
    Route::get('bills/customer-info', [BillController::class, 'customerInfo'])->name('bills.customer-info');
    Route::get('bills/{bill}/pdf', [BillController::class, 'pdf'])->name('bills.pdf');
    Route::resource('bills', BillController::class)->only(['index', 'create', 'store', 'show']);

    // Payments & Ledger
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('ledger/{customer}', [PaymentController::class, 'ledger'])->name('ledger.show');

    // Reports
    Route::get('reports/balance-sheet', [\App\Http\Controllers\Reports\BalanceSheetController::class, 'index'])->name('reports.balance-sheet');

    // Documentation
    Route::prefix('docs')->name('docs.')->group(function () {
        Route::get('/', [DocumentationController::class, 'index'])->name('index');
        Route::get('getting-started', [DocumentationController::class, 'gettingStarted'])->name('getting-started');
        Route::get('project-flow', [DocumentationController::class, 'projectFlow'])->name('project-flow');
        Route::get('business-guide', [DocumentationController::class, 'businessGuide'])->name('business-guide');
        Route::get('modules', [DocumentationController::class, 'modules'])->name('modules');
    });
    Route::get('reports/balance-sheet/bill/{transaction}', [\App\Http\Controllers\Reports\BalanceSheetController::class, 'billDetail'])->name('reports.balance-sheet.bill');
    Route::get('reports/balance-sheet/{customer}', [\App\Http\Controllers\Reports\BalanceSheetController::class, 'show'])->name('reports.balance-sheet.customer');
});
