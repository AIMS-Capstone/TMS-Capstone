<?php

use App\Http\Controllers\CoaController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\OrgAccountController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomVerificationController;
use App\Http\Controllers\sendPasswordReset;
use App\Http\Controllers\OrgSetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaxReturnController;
use App\Http\Middleware\CheckOrganizationSession;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CashReceiptController;
use App\Http\Controllers\CashDisbursementController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\GeneralJournalController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\UserController;
use App\Models\rdo;
use App\Models\TaxReturn;

// Public Routes
Route::get('/predictive-analytics', [PredictionController::class, 'getPredictions'])->name('predictive-analytics');
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
    Route::post('/org-delete', [OrgSetupController::class, 'destroy'])->name('orgSetup.destroy');
    Route::post('/org-account-destroy', [OrgAccountController::class, 'destroy'])->name('orgaccount.destroy');
    Route::post('/user-account-destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/user-account-store', [UserController::class, 'store'])->name('users.store');


    Route::post('/org_accounts', [OrgAccountController::class, 'store'])->name('org_accounts.store');
    // User Management
    Route::get('/user-management/user', [UserController::class, 'user'])->name('user-management.user');
    Route::get('/user-management/client', [UserController::class, 'client'])->name('user-management.client');
    
    // Routes Requiring Organization Session
    Route::middleware([CheckOrganizationSession::class])->group(function () {
        
        Route::get('/recycle-bin', function () {
            return view('recycle-bin');
        })->name('recycle-bin');

        Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

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


    //General Accounting Routing
        Route::get('/general-ledger', [GeneralLedgerController::class, 'ledger'])->name('general-ledger');
        Route::get('/general-ledger/export', [GeneralLedgerController::class, 'exportExcel'])->name('ledger.exportExcel');

    //Sales Book routings
        Route::get('/sales-book', [SalesController::class, 'sales'])->name('sales-book');
        Route::get('sales-book/posted', [SalesController::class, 'posted'])->name('posted');
        Route::post('/sales-book/post', [SalesController::class, 'updateToPosted'])->name('.updateToPosted');
        Route::post('/sales-book/draft', [SalesController::class, 'updateToDraft'])->name('.updateToDraft');
        Route::get('/sales-book/export', [SalesController::class, 'exportSalesBook'])->name('sales.exportExcel');
        Route::get('/sales-book-posted/export', [SalesController::class, 'exportSalesBookPosted'])->name('sales-posted.exportExcel');

    //Purchase Book routings
        Route::get('/purchase-book', [PurchaseController::class, 'purchase'])->name('purchase-book');
        Route::get('purchase-book/posted', [PurchaseController::class, 'posted'])->name('posted');
        Route::post('/purchase-book/post', [PurchaseController::class, 'updateToPosted'])->name('.updateToPosted');
        Route::post('/purchase-book/draft', [PurchaseController::class, 'updateToDraft'])->name('.updateToDraft');
        Route::get('/purchase-book/export', [PurchaseController::class, 'exportPurchaseBook'])->name('purchase.exportExcel');
        Route::get('/purchase-book-posted/export', [PurchaseController::class, 'exportPurchaseBookPosted'])->name('purchase-posted.exportExcel');



    //Cash receipt routings
        Route::get('/cash-receipt', [CashReceiptController::class, 'cashReceipt'])->name('cash-receipt');
        Route::get('cash-receipt/posted', [CashReceiptController::class, 'posted'])->name('posted');
        Route::post('/cash-receipt/post', [CashReceiptController::class, 'updateToPosted'])->name('.updateToPosted');
        Route::post('/cash-receipt/draft', [CashReceiptController::class, 'updateToDraft'])->name('.updateToDraft');
        Route::get('/cash-receipt/export', [CashReceiptController::class, 'exportCashReceipt'])->name('cash_receipt.exportExcel');
        Route::get('/cash-receipt-posted/export', [CashReceiptController::class, 'exportCashReceiptPosted'])->name('cash_receipt-posted.exportExcel');


    //Cash disbursement routings
        Route::get('/cash-disbursement', [CashDisbursementController::class, 'cashDisbursement'])->name('cash-disbursement');
        Route::get('cash-disbursement/posted', [CashDisbursementController::class, 'posted'])->name('posted');
        Route::post('/cash-disbursement/post', [CashDisbursementController::class, 'updateToPosted'])->name('.updateToPosted');
        Route::post('/cash-disbursement/draft', [CashDisbursementController::class, 'updateToDraft'])->name('.updateToDraft');
        Route::get('/cash-disbursement/export', [CashDisbursementController::class, 'exportCashDisbursement'])->name('cash_disbursement.exportExcel');
        Route::get('/cash-disbursement-posted/export', [CashDisbursementController::class, 'exportCashDisbursementPosted'])->name('cash_disbursement-posted.exportExcel');



    //General Journal Routing
        Route::get('/general-journal', [GeneralJournalController::class, 'journal'])->name('general-journal');
        Route::get('/general-journal/export', [GeneralJournalController::class, 'exportJournalExcel'])->name('journal.exportExcel');


    // Financial Reports Routing
        Route::get('/financial-reports', [FinancialController::class, 'financial'])->name('financial-reports');
        Route::get('/financial/export-excel', [FinancialController::class, 'exportExcel'])->name('financial.exportExcel');


        Route::get('/vat_report_pdf', function () {
            return view('tax_return.vat_report_pdf'); // Make sure to create this view file
        })->name('vat_report_pdf');



      

    });
   
});
