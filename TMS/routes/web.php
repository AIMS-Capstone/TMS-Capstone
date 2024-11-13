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
use App\Http\Middleware\ClientAuth;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CashReceiptController;
use App\Http\Controllers\CashDisbursementController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\GeneralJournalController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\AtcController;
use App\Http\Controllers\UserController;

//Recycle Bin
use App\Http\Controllers\OrganizationRecycleBinController; //This is the default for layouts
use App\Http\Controllers\AccountantUsersRecycleBinController;
use App\Http\Controllers\ClientUsersRecycleBinController;
use App\Http\Controllers\TransactionsRecycleBinController;
use App\Http\Controllers\TaxReturnsRecycleBinController;

//Client
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientFinancialController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\ClientAnalyticsController;
use App\Http\Controllers\TaxTypeController;
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
    Route::get('/export-atc/{type}', [AtcController::class, 'exportAtcs']);
    Route::get('/export-coa', [CoaController::class, 'exportCoas']);
    Route::get('/export-tax-type/{type}', [TaxTypeController::class, 'exportTaxType']);
    Route::get('/edit-sales/{transaction}', [TransactionsController::class, 'editSales']);
    
    Route::get('/transactions/{id}/edit-sales', [TransactionsController::class, 'edit'])->name('transactions.edit');
    Route::post('/transactions/{id}', [TransactionsController::class, 'update'])->name('transactions.update');

    Route::post('/org_accounts', [OrgAccountController::class, 'store'])->name('org_accounts.store');

    Route::middleware(['auth', 'check.role:Accountant'])->group(function () {
        // User Management
        Route::get('/user-management/user', [UserController::class, 'user'])->name('user-management.user');
        Route::get('/user-management/client', [UserController::class, 'client'])->name('user-management.client');

        //Recycle Bin Routing   
        Route::prefix('recycle-bin')->group(function () {
            // Organization
            Route::get('/organization', [OrganizationRecycleBinController::class, 'index'])->name('recycle-bin.organization.index');
            Route::post('/organization/bulk-restore', [OrganizationRecycleBinController::class, 'bulkRestore'])->name('recycle-bin.organization.bulkRestore');
            Route::delete('/organization/bulk-delete', [OrganizationRecycleBinController::class, 'bulkDelete'])->name('recycle-bin.organization.bulkDelete');

            // Accountant Users
            Route::get('/accountant-users', [AccountantUsersRecycleBinController::class, 'index'])->name('recycle-bin.accountant-users.index');
            Route::post('/accountant-users/bulk-restore', [AccountantUsersRecycleBinController::class, 'bulkRestore'])->name('recycle-bin.accountant-users.bulkRestore');
            Route::delete('/accountant-users/bulk-delete', [AccountantUsersRecycleBinController::class, 'bulkDelete'])->name('recycle-bin.accountant-users.bulkDelete');

            // Client Users
            Route::get('/client-users', [ClientUsersRecycleBinController::class, 'index'])->name('recycle-bin.client-users.index');
            Route::post('/client-users/bulk-restore', [ClientUsersRecycleBinController::class, 'bulkRestore'])->name('recycle-bin.client-users.bulkRestore');
            Route::delete('/client-users/bulk-delete', [ClientUsersRecycleBinController::class, 'bulkDelete'])->name('recycle-bin.client-users.bulkDelete');

            // Transactions
            Route::get('/transactions', [TransactionsRecycleBinController::class, 'index'])->name('recycle-bin.transactions.index');
            Route::post('/transactions/bulk-restore', [TransactionsRecycleBinController::class, 'bulkRestore'])->name('recycle-bin.transactions.bulkRestore');
            Route::delete('/transactions/bulk-delete', [TransactionsRecycleBinController::class, 'bulkDelete'])->name('recycle-bin.transactions.bulkDelete');

            // Tax Returns
            Route::get('/tax-returns', [TaxReturnsRecycleBinController::class, 'index'])->name('recycle-bin.tax-returns.index');
            Route::post('/tax-returns/bulk-restore', [TaxReturnsRecycleBinController::class, 'bulkRestore'])->name('recycle-bin.tax-returns.bulkRestore');
            Route::delete('/tax-returns/bulk-delete', [TaxReturnsRecycleBinController::class, 'bulkDelete'])->name('recycle-bin.tax-returns.bulkDelete');
        });//ending of recycle-bin prefix
    });//ending of auth role middleware


    // Routes Requiring Organization Session
    Route::middleware([CheckOrganizationSession::class])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

        // Transactions Routes
        Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions');
        Route::get('/transactions/upload', [TransactionsController::class, 'showUploadForm'])->name('receipts.uploadForm');
        Route::post('/transaction/upload', [TransactionsController::class, 'upload'])->name('transactions.upload');
        Route::get('/transactions/create', [TransactionsController::class, 'create']);
        Route::get('/transactions/{transaction}', [TransactionsController::class, 'show'])->name('transactions.show');
        Route::post('/transaction/destroy', [TransactionsController::class, 'destroy']);
        Route::get('/transaction/download', [TransactionsController::class, 'download_transaction']);
        Route::post('/transactions/store/upload', [TransactionsController::class, 'storeUpload'])->name('transactions.storeUpload');

        // Contacts Routes
        Route::get('/contacts', function () {
            return view('contacts');
        })->name('contacts');

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

    });//ending of organization middleware
});//ending of built in auth of Laravel Jetstream

    //client-side routings

    //clients verification
    Route::get('/client/login', [ClientAuthController::class, 'showLoginForm'])->name('client.login');
    Route::post('/client/login', [ClientAuthController::class, 'login']);
    Route::post('/client/logout', [ClientAuthController::class, 'logout'])->name('client.logout');

    // Client forgot password routes
    Route::get('/client/password/forgot', [ClientAuthController::class, 'showForgotPasswordForm'])->name('client.auth.password.request');
    Route::post('/client/password/email', [ClientAuthController::class, 'sendResetLink'])->name('client.password.email');
    Route::get('/client/password/reset/{token}', [ClientAuthController::class, 'showResetPasswordForm'])->name('client.password.reset');
    Route::post('/client/password/reset', [ClientAuthController::class, 'resetPassword'])->name('client.password.update');

    Route::get('/client/check-mail', function () {
        return view('client.check-mail');
    })->name('client.check-mail');


    // Client confirm password route
    Route::get('/client/password/confirm', [ClientAuthController::class, 'showConfirmPasswordForm'])->name('client.password.confirm');
    Route::post('/client/password/confirm', [ClientAuthController::class, 'confirmPassword']);

    // Protect client-specific routes
    Route::middleware(['client.auth', 'client.organization'])->group(function () {
        Route::get('/client/dashboard', [ClientAuthController::class, 'dashboard'])->name('client.dashboard');
        Route::get('/client/forms', [ClientAuthController::class, 'forms'])->name('client.forms');
        Route::get('/client/income_statement', [ClientFinancialController::class, 'income_statement'])->name('client.income_statement');
        Route::get('/client/income_statement/export-excel', [ClientFinancialController::class, 'clientExportExcel'])->name('client.financial.exportExcel');

        Route::get('/client/analytics', [ClientAnalyticsController::class, 'analytics'])->name('client.analytics');

        Route::get('/client/profile', [ClientProfileController::class, 'profile'])->name('client.profile');
        Route::post('/client/profile/update_photo', [ClientProfileController::class, 'updateProfilePhoto'])->name('client.profile.update_photo');
        Route::post('/client/profile/update_email', [ClientProfileController::class, 'updateEmail'])->name('client.profile.update_email');
        
        Route::get('/client/settings', [ClientAuthController::class, 'settings'])->name('client.settings');


    });
