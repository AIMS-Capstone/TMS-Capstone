<?php

use App\Http\Controllers\CoaController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomVerificationController;
use App\Http\Controllers\sendPasswordReset;
use App\Http\Controllers\OrgSetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaxReturnController;
use App\Http\Middleware\CheckOrganizationSession;
use App\Models\rdo;
use App\Models\TaxReturn;

// Public Routes
Route::get('/', function () {
    return view('auth/login');
});

Route::get('/reset-password', function () {
    return view('auth/reset-password');
});

Route::post('/custom-password-email', [sendPasswordReset::class, 'sendPasswordReset'])->name('custom.password.email');
Route::get('/check-mail', function () {
    return view('components/check-mail');
});

Route::get('/register-success-page', function () {      
    return view('components/register-success-page');
})->name('register-success-page');

// Email Verification Route
Route::get('/email/verify/{id}/{hash}', [CustomVerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);

// Authenticated Routes with Middleware (auth, check organization session where required)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard & Organization Setup Routes
    Route::post('/org-dashboard', [OrgSetupController::class, 'setOrganization'])->name('org-dashboard');
    
    Route::get('/create-org', function() {
        $rdos = rdo::all();
        $regions = json_decode(file_get_contents(public_path('json/regions.json')), true);
        $provinces = json_decode(file_get_contents(public_path('json/provinces.json')), true);
        $municipalities = json_decode(file_get_contents(public_path('json/municipalities.json')), true);
        return view('components.create-org', compact('rdos', 'regions', 'provinces', 'municipalities'));
    })->name('create-org');

    Route::get('/org-setup', [OrgSetupController::class, 'index'])->name('org-setup');
    Route::post('/org-setup', [OrgSetupController::class, 'store'])->name('OrgSetup.store');

    // Routes Requiring Organization Session
    Route::middleware([CheckOrganizationSession::class])->group(function () {
        // User Management
        Route::get('/user-management', function () {
            return view('user-management');
        })->name('user-management');

        Route::get('/recycle-bin', function () {
            return view('recycle-bin');
        })->name('recycle-bin');

        Route::get('/dashboard', function() {
            return view('dashboard');
        })->name('dashboard');

        // Transactions Routes
        Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions');
        Route::get('/transactions/upload', [TransactionsController::class, 'showUploadForm'])->name('receipts.uploadForm');
        Route::post('/transactions/upload', [TransactionsController::class, 'upload'])->name('transactions.upload');
        Route::get('/transactions/create', [TransactionsController::class, 'create']);
        Route::get('/transactions/{transaction}', [TransactionsController::class, 'show'])->name('transactions.show');

        // Tax Return Routes
        Route::resource('tax_return', TaxReturnController::class);
        Route::get('/vat_return', function () {
            $organizationId = session('organization_id');
            $taxReturns = TaxReturn::with('user')->where('organization_id', $organizationId)->get();
            return view('tax_return/vat_return', compact('taxReturns'));
        })->name('vat_return');
        Route::post('/vat_return', [TaxReturnController::class, 'store']);
        Route::get('/tax_return/{taxReturn}/slsp-data', [TaxReturnController::class, 'showSlspData'])->name('tax_return.slsp_data');
        Route::get('/summary', [TaxReturnController::class, 'showSummary'])->name('summary');
        Route::get('/tax_return/{taxReturn}/report', [TaxReturnController::class, 'showReport'])->name('tax_return.report');
        Route::get('/notes-activity', [TaxReturnController::class, 'showNotesActivity'])->name('notes_activity');

        // Charts of Accounts (CoA) Routes
        Route::get('/coa', [CoaController::class, 'index'])->name('coa');
        Route::post('/coa', [CoaController::class, 'store'])->name('coa.store');
        Route::post('/coa/delete', [CoaController::class, 'destroy'])->name('coa.delete');
        Route::get('/coa/archive', [CoaController::class, 'archive'])->name('archive');
        Route::post('/coa/restore', [CoaController::class, 'restore'])->name('coa.restore');
        Route::post('/coa/deactivate', [CoaController::class, 'deactivate'])->name('coa.deactivate');
        Route::get('/download_coa', [CoaController::class, 'download_coa']);
        Route::get('/coa/import_template', [CoaController::class, 'import_template']);
        Route::get('/coa/account_type_template', [CoaController::class, 'account_type_template']);
        Route::put('/coa/{coa}', [CoaController::class, 'update'])->name('coa.update');

        // General Accounting Routes
        Route::get('/general-ledger', function () {
            return view('general-ledger');
        })->name('general-ledger');

        Route::get('/sales-book', function () {
            return view('sales-book');
        })->name('sales-book');

        Route::get('/purchase-book', function () {
            return view('purchase-book');
        })->name('purchase-book');

        Route::get('/vat_report_pdf', function () {
            return view('tax_return.vat_report_pdf'); // Make sure to create this view file
        })->name('vat_report_pdf');
        
        Route::get('/cash-receipt', function () {
            return view('cash-receipt');
        })->name('cash-receipt');

        Route::get('/cash-disb', function () {
            return view('cash-disb');
        })->name('cash-disb');

        Route::get('/general-journal', function () {
            return view('general-journal');
        })->name('general-journal');

        // Financial Reports & Predictive Analytics
        Route::get('/financial-reports', function () {
            return view('financial-reports');
        })->name('financial-reports');

        Route::get('/predictive-analytics', function () {
            return view('predictive-analytics');
        })->name('predictive-analytics');

    });
});
