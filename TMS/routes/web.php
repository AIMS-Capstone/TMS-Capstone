<?php

use App\Http\Controllers\BackgroundInformationController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\OrgAccountController;
use App\Http\Controllers\TaxOptionRateController;
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
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\TaxTypeController;
use App\Http\Controllers\Tax1701QController;

// WithHolding
use App\Http\Controllers\WithHoldingController; // this is the default for withHolding, where the 1601C resides
// Hiniwalay ko na yung controller, aabot ng 3k lines e
use App\Http\Controllers\withHolding0619EController;
use App\Http\Controllers\withHolding1601EQController;
use App\Http\Controllers\withHolding1604CController;
use App\Http\Controllers\withHolding1604EController;

//Recycle Bin
use App\Http\Controllers\OrganizationRecycleBinController; //This is the default for layouts
use App\Http\Controllers\AccountantUsersRecycleBinController;
use App\Http\Controllers\ClientUsersRecycleBinController;
use App\Http\Controllers\TransactionsRecycleBinController;
use App\Http\Controllers\TaxReturnsRecycleBinController;
use App\Http\Controllers\ContactsRecycleBinController;
use App\Http\Controllers\EmployeesRecycleBinController;

//Notes and Activities
use App\Http\Controllers\ActivityLogController;

//Client
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientFinancialController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\ClientAnalyticsController;

//Models
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
    Route::get('/tax-returns/{taxReturn}/percentage-summary', [TaxReturnController::class, 'showPercentageSummaryPage'])
    ->name('tax-returns.percentage-summary');
    Route::post('/tax-return-transaction/sales', [TransactionsController::class, 'getSalesTransactions']);
    Route::post('/tax-return-transaction/all_transactions', [TransactionsController::class, 'getAllTransactions']);
    Route::post('/tax-return-transaction/add-percentage', [TransactionsController::class, 'addPercentage'])->name('tax_return_transaction.addPercentage');
    Route::post('/tax-return-transaction/add-transaction', [TransactionsController::class, 'addTransaction'])->name('tax_return_transaction.addTransaction');
    Route::get('/org-setup', [OrgSetupController::class, 'index'])->name('org-setup');
    Route::post('/org-setup', [OrgSetupController::class, 'store'])->name('OrgSetup.store');
    Route::put('/org-setup/{id}', [OrgSetupController::class, 'update'])->name('org-setup.update');
    Route::post('/org-delete', [OrgSetupController::class, 'destroy'])->name('orgSetup.destroy');
    Route::post('/org-account-destroy', [OrgAccountController::class, 'destroy'])->name('orgaccount.destroy');
    Route::post('/user-account-destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/user-account-store', [UserController::class, 'store'])->name('users.store');
    Route::get('/export-atc/{type}', [AtcController::class, 'exportAtcs'])->name('export.atcs');
    Route::get('/export-coa', [CoaController::class, 'exportCoas']);
    Route::get('/export-tax-type/{type}', [TaxTypeController::class, 'exportTaxType'])->name('export.taxType');
    Route::get('/edit-sales/{transaction}', [TransactionsController::class, 'editSales']);
    Route::get('/tax-return/{taxReturn}/2551q-pdf', [TaxReturnController::class, 'showPercentageReportPDF'])
    ->name('tax_return.2551q.pdf');
    Route::get('/tax-return/{taxReturn}/2550q-pdf', [TaxReturnController::class, 'showVatReportPDF'])
    ->name('tax_return.2550q.pdf');
    
    Route::get('/transactions/{id}/edit-sales', [TransactionsController::class, 'edit'])->name('transactions.edit');
    Route::post('/transactions/{id}', [TransactionsController::class, 'update'])->name('transactions.update');
    Route::post('/tax-return/{id}/mark-filed', [TaxReturnController::class, 'markAsFiled'])->name('tax-return.mark-filed');

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
            Route::post('/tax-returns/bulk-delete', [TaxReturnsRecycleBinController::class, 'bulkDelete'])->name('recycle-bin.tax-returns.bulkDelete');

            // Contacts
            Route::get('/contacts', [ContactsRecycleBinController::class, 'index'])->name('recycle-bin.contacts.index');
            Route::post('/contacts/bulk-restore', [ContactsRecycleBinController::class, 'bulkRestore'])->name('recycle-bin.contacts.bulkRestore');
            Route::delete('/contacts/bulk-delete', [ContactsRecycleBinController::class, 'bulkDelete'])->name('recycle-bin.contacts.bulkDelete');

            // Employees
            Route::get('/employees', [EmployeesRecycleBinController::class, 'index'])->name('recycle-bin.employees.index');
            Route::post('/employees/bulk-restore', [EmployeesRecycleBinController::class, 'bulkRestore'])->name('recycle-bin.employees.bulkRestore');
            Route::delete('/employees/bulk-delete', [EmployeesRecycleBinController::class, 'bulkDelete'])->name('recycle-bin.employees.bulkDelete');

        });//ending of recycle-bin prefix

    //Notes and Activities
        Route::get('/audit_log/index', [ActivityLogController::class, 'audit_log'])->name('audit_log');

    });//ending of auth role middleware


    // Routes Requiring Organization Session
    Route::middleware([CheckOrganizationSession::class])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

        // Transactions Routes
        Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions');
        Route::get('/transactions/upload', [TransactionsController::class, 'showUploadForm'])->name('receipts.uploadForm');
        Route::post('/transaction/upload', [TransactionsController::class, 'upload'])->name('transactions.upload');
        Route::get('/transactions/create', [TransactionsController::class, 'create'])->name('transactions.create');
        Route::get('/transactions/{transaction}', [TransactionsController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{transactionId}/edit', [TransactionsController::class, 'edit'])->name('transactions.edit');
        Route::get('/transactions/{transaction}/mark', [TransactionsController::class, 'mark'])->name('transactions.mark');
Route::post('/transactions/mark-as-paid/{transaction}', [TransactionsController::class, 'markAsPaid'])->name('transactions.markAsPaid');


        Route::post('/transaction/destroy', [TransactionsController::class, 'destroy']);
        Route::get('/transaction/download', [TransactionsController::class, 'download_transaction']);
        Route::post('/transactions/store/upload', [TransactionsController::class, 'storeUpload'])->name('transactions.storeUpload');


Route::post('/tax-return/store1701Q/{taxReturn}', [Tax1701QController::class, 'store1701Q'])->name('tax_return.store1701Q');

        Route::controller(ContactsController::class)->group(function () {
            Route::get('/contacts', 'index')->name('contacts');
            Route::post('/contacts', 'store')->name('contacts.store');
            Route::put('/contacts/{contact}', [ContactsController::class, 'update'])->name('contacts.update');
            Route::delete('/contacts/destroy', 'destroy')->name('contacts.destroy'); 
        });

        // Employees Routes
        Route::controller(EmployeesController::class)->group(function () {
            Route::get('/employees', 'index')->name('employees');
            Route::post('/employees', 'store')->name('employees.store');
            Route::put('/employees/{employee}', [EmployeesController::class, 'update'])->name('employees.update'); 
            Route::delete('/employees/destroy', 'destroy')->name('employees.destroy');
            Route::get('/employees/employee_template', [EmployeesController::class, 'employee_template']);

        });

          Route::post('percentage_return/{taxReturn}/2551q', [TaxReturnController::class, 'store2551Q'])
          ->name('tax_return.store2551Q');
        Route::post('tax-return/{taxReturn}/2550q', [TaxReturnController::class, 'store2550Q'])
        ->name('tax_return.store2550Q');
        Route::post('/tax-return-transaction/deactivate', [TransactionsController::class, 'deactivate'])
    ->name('tax-return-transaction.deactivate');
    // This is for deactivating transactions on Spouse or Individual
    Route::post('/tax-return-transaction/deactivate_transaction', [TransactionsController::class, 'deactivateTransaction'])
    ->name('tax-return-transaction.deactivateTransaction');
        // Tax Return Routes
        Route::resource('tax_return', TaxReturnController::class);
        Route::get('/vat_return', [TaxReturnController::class, 'vatReturn'])->name('vat_return');
        Route::get('/income_return', [TaxReturnController::class, 'incomeReturn'])->name('income_return');
        Route::get('/percentage_return/{id}/report', [TaxReturnController::class, 'showPercentageReport'])
    ->name('percentage_return.report');
    Route::get('/percentage_return/{id}/edit', [TaxReturnController::class, 'percentageEdit'])
    ->name('percentage_return.edit');
    Route::get('/vat_return/{id}/report', [TaxReturnController::class, 'showVatReport'])
    ->name('tax_return.report');
    Route::get('/income_return/{id}/report', [TaxReturnController::class, 'showIncomeReport'])
    ->name('income_return.report');


        Route::get('tax_return/{id}/income-input-summary', [TaxReturnController::class, 'showIncomeInputSummary'])->name('tax_return.income_input_summary');
        
        // Individual sections
        Route::get('tax_return/{id}/generate_report', [TaxReturnController::class, 'editTaxOptionRate'])->name('tax_return.generate_report');
        Route::get('tax_return/{id}/notes_activities', [TaxReturnController::class, 'editTaxOptionRate'])->name('tax_return.notes_activities');
        Route::get('/tax-return/{id}/tax-option-rate', [TaxOptionRateController::class, 'edit'])
    ->name('tax_return.tax_option_rate');

    Route::put('/tax-return/{id}/tax-option-rate', [TaxOptionRateController::class, 'update'])->name('tax_return.tax_option_rate.update');

    Route::get('tax-return/{id}/background-information', [BackgroundInformationController::class, 'edit'])
    ->name('tax_return.background_information')
    ->where('id', '[0-9]+');  // Ensure the id is a number
    Route::get('/tax-return/{taxReturn}/income_sales', [TaxReturnController::class, 'showIncomeSalesData'])->name('tax_return.income_show_sales');
    Route::get('/tax-return/{taxReturn}/income_coa', [TaxReturnController::class, 'showIncomeCoaData'])->name('tax_return.income_show_coa');
Route::put('tax-return/{id}/background-information', [BackgroundInformationController::class, 'update'])
    ->name('tax_return.background_information.update')
    ->where('id', '[0-9]+');  // Ensure the id is a number
        Route::get('tax_return/{id}/spouse-information', [TaxReturnController::class, 'editSpouseInformation'])->name('tax_return.spouse_information');
        Route::get('tax_return/{id}/sales-revenue', [TaxReturnController::class, 'editSalesRevenue'])->name('tax_return.sales_revenue');

    Route::post('/tax_return/destroy',[TaxReturnController::class, 'destroy']);
    Route::get('/income_return/{id}/{type}', [TaxReturnController::class, 'showIncome'])->name('income_return.show');

        Route::get('/percentage_return', [TaxReturnController::class, 'percentageReturn'])->name('percentage_return');
        Route::get('/percentage_return/{taxReturn}/slsp-data', [TaxReturnController::class, 'showPercentageSlspData'])->name('percentage_return.slsp_data');
        Route::post('/percentage_return', [TaxReturnController::class, 'storePercentage']);
        Route::post('/vat_return', [TaxReturnController::class, 'store']);
        Route::post('/income_return', [TaxReturnController::class, 'storeIncome']);
        Route::get('/tax_return/{taxReturn}/slsp-data', [TaxReturnController::class, 'showSlspData'])->name('tax_return.slsp_data');
        Route::get('/tax_return/{taxReturn}/summary', [TaxReturnController::class, 'showSummary'])->name('tax_return.summary');
        Route::get('/summary', [TaxReturnController::class, 'showSummary'])->name('summary');
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
        Route::get('sales-book/posted', [SalesController::class, 'posted'])->name('salesPosted');
        Route::post('/sales-book/post', [SalesController::class, 'updateToPosted'])->name('.updateToPosted');
        Route::post('/sales-book/draft', [SalesController::class, 'updateToDraft'])->name('.updateToDraft');
        Route::get('/sales-book/export', [SalesController::class, 'exportSalesBook'])->name('sales.exportExcel');
        Route::get('/sales-book-posted/export', [SalesController::class, 'exportSalesBookPosted'])->name('sales-posted.exportExcel');

        //Purchase Book routings
        Route::get('/purchase-book', [PurchaseController::class, 'purchase'])->name('purchase-book');
        Route::get('purchase-book/posted', [PurchaseController::class, 'posted'])->name('purchasePosted');
        Route::post('/purchase-book/post', [PurchaseController::class, 'updateToPosted'])->name('.updateToPosted');
        Route::post('/purchase-book/draft', [PurchaseController::class, 'updateToDraft'])->name('.updateToDraft');
        Route::get('/purchase-book/export', [PurchaseController::class, 'exportPurchaseBook'])->name('purchase.exportExcel');
        Route::get('/purchase-book-posted/export', [PurchaseController::class, 'exportPurchaseBookPosted'])->name('purchase-posted.exportExcel');

        //Cash receipt routings
        Route::get('/cash-receipt', [CashReceiptController::class, 'cashReceipt'])->name('cash-receipt');
        Route::get('cash-receipt/posted', [CashReceiptController::class, 'posted'])->name('receiptPosted');
        Route::post('/cash-receipt/post', [CashReceiptController::class, 'updateToPosted'])->name('.updateToPosted');
        Route::post('/cash-receipt/draft', [CashReceiptController::class, 'updateToDraft'])->name('.updateToDraft');
        Route::get('/cash-receipt/export', [CashReceiptController::class, 'exportCashReceipt'])->name('cash_receipt.exportExcel');
        Route::get('/cash-receipt-posted/export', [CashReceiptController::class, 'exportCashReceiptPosted'])->name('cash_receipt-posted.exportExcel');

        //Cash disbursement routings
        Route::get('/cash-disbursement', [CashDisbursementController::class, 'cashDisbursement'])->name('cash-disbursement');
        Route::get('cash-disbursement/posted', [CashDisbursementController::class, 'posted'])->name('disbPosted');
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

        // With Holding tax
        Route::prefix('/tax_return/with_holding')->group(function () {

            // Routes for generating withholding tax return for 1601C
                Route::get('/1601C', [WithHoldingController::class, 'index1601C'])->name('with_holding.1601C');
                Route::post('/1601C/generate', [WithHoldingController::class, 'generate1601C'])->name('with_holding.1601C.generate');
                Route::post('/1601C/destroy', [WithHoldingController::class, 'destroy1601C'])->name('with_holding.1601C.destroy');
                Route::get('/{id}/1601C_summary', [WithHoldingController::class, 'showSummary1601C'])->name('with_holding.1601C_summary');
                Route::get('/{id}/1601C_sources', [WithHoldingController::class, 'showSources1601C'])->name('with_holding.1601C_sources');
                Route::post('/{id}/1601C_sources_store', [WithHoldingController::class, 'store1601C'])->name('with_holding.1601C_sources_store');
                // Route for creating the 1601C form
                Route::get('/{id}/1601C_form', [WithHoldingController::class, 'createForm1601C'])->name('form1601C.create');
                Route::post('/{id}/1601C/store', [WithHoldingController::class, 'storeForm1601C'])->name('form1601C.store');
                //Route for import
                Route::post('/withholding/1601C/{id}/import', [WithHoldingController::class, 'importSources1601C'])->name('with_holding.1601C_import');


            // Routes for generting withholding tax return for 0619E
                Route::get('/0619E', [withHolding0619EController::class, 'index0619E'])->name('with_holding.0619E');
                Route::post('/0619E/generate', [withHolding0619EController::class, 'generate0619E'])->name('with_holding.0619E.generate');
                Route::post('/0619E/destroy ', [withHolding0619EController::class, 'destroy0619E'])->name('with_holding.0619E.destroy');
                // Route for creating the 0619E
                Route::get('/{id}/0619E_form', [withHolding0619EController::class, 'createForm0619E'])->name('form0619E.create');
                Route::post('/{id}/0619E/store', [withHolding0619EController::class, 'storeForm0619E'])->name('form0619E.store');
            
            // Routes for generating withholding tax return for 1601EQ
                Route::get('/1601EQ', [withHolding1601EQController::class, 'index1601EQ'])->name('with_holding.1601EQ');
                Route::post('/1601EQ/generate', [withHolding1601EQController::class, 'generate1601EQ'])->name('with_holding.1601EQ.generate');
                Route::post('/1601EQ/destroy', [withHolding1601EQController::class, 'destroy1601EQ'])->name('with_holding.1601EQ.destroy');
                //QAP nig
                Route::get('/{id}/1601EQ_Qap', [withHolding1601EQController::class, 'showQap1601EQ'])->name('with_holding.1601EQ_Qap');
                Route::post('/{id}/1601EQ_Qap/set', [withHolding1601EQController::class, 'setQap1601EQ'])->name('with_holding.1601EQ_Qap.set');

                // Route for creating 1601EQ
                Route::get('/{id}/1601EQ_form', [withHolding1601EQController::class, 'createForm1601EQ'])->name('form1601EQ.create');
                Route::post('/{id}/1601EQ/store', [withHolding1601EQController::class, 'storeForm1601EQ'])->name('form1601EQ.store');
                
            // Route for generating witholding tax return for 1604C
                Route::get('/1604C', [withHolding1604CController::class, 'index1604C'])->name('with_holding.1604C');
                Route::post('/1604C/generate', [withHolding1604CController::class, 'generate1604C'])->name('with_holding.1604C.generate');
                // creating 1604C
                Route::get('/{id}/1604C_remittances', [withHolding1604CController::class, 'showRemittance1604C'])->name('with_holding.1604C_remittances');
                // Routes for 1604C sources
                Route::get('/{id}/1604C_schedule1', [withHolding1604CController::class, 'show1604Cschedule1'])->name('with_holding.1604C_schedule1');
                Route::get('/{id}/1604C_schedule2', [withHolding1604CController::class, 'show1604Cschedule2'])->name('with_holding.1604C_schedule2');
                // Generating hatdog na 1604C
                Route::get('/{id}/1604C_form', [withHolding1604CController::class, 'createForm1604C'])->name('form1604C.create');
                Route::post('/{id}/1604C/store', [withHolding1604CController::class, 'storeForm1604C'])->name('form1604C.store');

            // Route for generating witholding tax return for 1604E
                Route::get('/1604E', [withHolding1604EController::class, 'index1604E'])->name('with_holding.1604E');
                Route::post('/1604E/generate', [withHolding1604EController::class, 'generate1604E'])->name('with_holding.1604E.generate');
                //creating 1604E
                Route::get('/{id}/1604E_summary', [withHolding1604EController::class, 'showSummary1604E'])->name('with_holding.1604E_summary');
                Route::get('/{id}/1604E_remittances', [withHolding1604EController::class, 'showRemittance1604E'])->name('with_holding.1604E_remittances');
                Route::get('/{id}/1604E_sources', [WithHolding1604EController::class, 'showSources1604E'])->name('with_holding.1604E_sources');
                Route::get('/{id}/1604E_schedule4', [WithHolding1604EController::class, 'showSchedule41604E'])->name('with_holding.1604E_schedule4');
                Route::post('/{id}/1604E_schedule4', [WithHolding1604EController::class, 'storeSchedule41604E'])->name('with_holding.1604E_store');

                Route::get('/{id}/1604E_form', [withHolding1604EController::class, 'createForm1604E'])->name('form1604E.create');
                Route::post('/{id}/1604E/store', [withHolding1604EController::class, 'storeForm1604E'])->name('form1604E.store');


        });//ending of tax_return/with_holding prefix

        //Notes and Activities
        Route::get('/audit_log/index', [ActivityLogController::class, 'audit_log'])->name('audit_log.index');

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