<?php

use App\Http\Controllers\CoaController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomVerificationController;
use App\Http\Controllers\sendPasswordReset;
use App\Http\Controllers\OrgSetupController;
use App\Http\Controllers\DashboardController;
use App\Models\rdo;

Route::get('/', function () {
    return view('auth/login');
});
Route::get('/transactions/upload', [TransactionsController::class, 'showUploadForm'])->name('receipts.uploadForm');

Route::post('/transactions/upload', [TransactionsController::class, 'upload'])->name('transactions.upload');


Route::get('/reset-password', function () {
    return view('auth/reset-password');
});

Route::post('/custom-password-email', [sendPasswordReset::class, 'sendPasswordReset'])->name('custom.password.email');

Route::get('/check-mail', function () {
    return view('components/check-mail');
});

Route::get('/transactions/create', [TransactionsController::class, 'create']);
Route::get('/transactions/{transaction}', [TransactionsController::class, 'show'])->name('transactions.show');


Route::get('/email/verify/{id}/{hash}', [CustomVerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);

Route::get('/register-success-page', function () {      
    return view('components/register-success-page');
})->name('register-success-page');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/recycle-bin', function () {
        return view('recycle-bin');
    })->name('recycle-bin');

    //url base, ayaw gumana undefined yung {orgID} kahit na defined ko yon sa controller. Hindi ko alam bakit, pero nakikita yung id# sa bawat url nung directory if nag hover dun sa select organization.
    Route::get('/dashboard/{orgId}', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/dashboard', [OrgSetupController::class, 'index'])->name('dashboard');
    Route::post('/org-dashboard', [OrgSetupController::class, 'setOrganization'])->name('org-dashboard');
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');
    
    //session base
    //Route::get('/dashboard', [DashboardController::class, 'show])->middleware(EnsureOrganizationSelected);



    Route::get('/general-ledger', function () {
        return view('general-ledger');
    })->name('general-ledger');
    Route::get('/sales-book', function () {
        return view('sales-book');
    })->name('sales-book');
    Route::get('/purchase-book', function () {
        return view('purchase-book');
    })->name('purchase-book');
    Route::get('/cash-receipt', function () {
        return view('cash-receipt');
    })->name('cash-receipt');
    Route::get('/cash-disb', function () {
        return view('cash-disb');
    })->name('cash-disb');
    Route::get('/general-journal', function () {
        return view('general-journal');
    })->name('general-journal');

    //Org Setup Routes
    Route::get('/create-org', function() {
        $rdos = rdo::all(); // Fetch all Rdo records
        $regions = json_decode(file_get_contents(public_path('json/regions.json')), true);
        $provinces = json_decode(file_get_contents(public_path('json/provinces.json')), true);
        $municipalities = json_decode(file_get_contents(public_path('json/municipalities.json')), true);
        return view('components.create-org', compact('rdos', 'regions', 'provinces', 'municipalities')); // Pass the $rdos variable to the view
    })->name('create-org');
    Route::get('/org-setup', [OrgSetupController::class, 'index'])->name('org-setup');
    Route::post('/org-setup', [OrgSetupController::class, 'store'])->name('OrgSetup.store');
    
Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions');


//Charts of Accounts routings

    Route::get('/coa', [CoaController::class, 'index'])->name('coa');
    Route::post('coa', [CoaController::class, 'store'])->name('coa.store');
    Route::post('/coa/delete', [CoaController::class, 'destroy'])->name('coa.delete'); 
    Route::get('coa/archive', [CoaController::class, 'archive'])->name('archive');
    Route::post('/coa/restore', [CoaController::class, 'restore'])->name('coa.restore');
    Route::post('/coa/deactivate', [CoaController::class, 'deactivate'])->name('coa.deactivate');
    Route::get('/download_coa', [CoaController::class, 'download_coa']);
Route::get('coa/import_template', [CoaController::class, 'import_template']);
Route::get('coa/account_type_template', [CoaController::class, 'account_type_template']);
Route::put('/coa/{coa}', [CoaController::class, 'update'])->name('coa.update');


    Route::get('/financial-reports', function () {
        return view('financial-reports');
    })->name('financial-reports');

    Route::get('/predictive-analytics', function () {
        return view('predictive-analytics');
    })->name('predictive-analytics');

    Route::get('/add-user', function () {
        return view('add-user');
    })->name('add-user');
});