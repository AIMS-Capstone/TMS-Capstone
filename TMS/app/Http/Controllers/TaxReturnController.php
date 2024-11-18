<?php

namespace App\Http\Controllers;

use App\Models\TaxReturn;
use App\Http\Requests\StoreTaxReturnRequest;
use App\Http\Requests\UpdateTaxReturnRequest;
use App\Models\OrgSetup;
use App\Models\TaxReturnTransaction;
use App\Models\TaxRow;
use App\Models\Transactions;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use mikehaertl\pdftk\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use FPDM;
use Illuminate\Support\Facades\Session;

class TaxReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function vatReturn()
    {
        $organizationId = session('organization_id');
        $taxReturns = TaxReturn::with('user')
            ->where('organization_id', $organizationId)
            ->whereIn('title', ['2550Q', '2550M'])
            ->get();

        return view('tax_return.vat_return', compact('taxReturns'));
    }
    public function showPercentageReport($id)
{
    $taxReturn = TaxReturn::findOrFail($id);
    $organization_id = session("organization_id");

    // Ensure correct relationship loading with lowercase 'rdo'
    $organization = OrgSetup::with("rdo")
        ->where('id', $organization_id)
        ->first();

        $rdoCode = optional($organization->Rdo)->rdo_code ?? '' ;

    // Parse the start_date using Carbon
    $startDate = Carbon::parse($organization->start_date);

    // Determine the year ended based on the start_date
    $yearEnded = null;
    if ($startDate->month == 1 && $startDate->day == 1) {
        // Calendar year - Ends on December 31 of the next year
        $yearEnded = $startDate->addYear()->endOfYear();
    } else {
        // Fiscal year - Ends on the last day of the same month next year
        $yearEnded = $startDate->addYear()->lastOfMonth();
    }

    // Check whether the organization follows a calendar or fiscal period
    $period = ($startDate->month == 1 && $startDate->day == 1) ? 'calendar' : 'fiscal';

    // Format the year ended date as 'YYYY-MM'
    $yearEndedFormatted = $yearEnded->format('Y-m');
    $yearEndedFormattedForDisplay = $yearEnded->format('m/Y'); // e.g., "12/2024"
   


    // Return the view with the necessary data
    return view('tax_return.percentage_report_preview', compact('taxReturn', 'organization', 'yearEndedFormatted', 'yearEndedFormattedForDisplay', 'period', 'rdoCode'));
}
    public function percentageReturn()
    {
        $organizationId = session('organization_id');
        $taxReturns = TaxReturn::with('user')
            ->where('organization_id', $organizationId)
            ->whereIn('title', ['2551Q', '2551M'])
            ->get();

        return view('tax_return.percentage_return', compact('taxReturns'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaxReturnRequest $request)
    {
        // Step 1: Create the tax return
        $taxReturn = TaxReturn::create([
            'title' => $request->type,
            'year' => $request->year, // This is the tax return's year (from the request)
            'month' => $request->month,
            'created_by' => auth()->id(),
            'organization_id' => $request->organization_id, 
            'status' => 'Unfiled',
        ]);
    
        // Step 2: Get the organization's start date
        $organization = OrgSetup::find($request->organization_id);
        $organizationStartDate = Carbon::parse($organization->start_date); // Assume start_date is a date field
    
        // Step 3: Determine the date range based on the selected month/quarter
        $startDate = null;
        $endDate = null;
    
        // If it's a month selection (1-12)
        if (is_numeric($request->month)) {
            // Use the tax return's year for month calculation
            $startDate = Carbon::create($request->year, $request->month, 1)->startOfMonth();
            $endDate = Carbon::create($request->year, $request->month, 1)->endOfMonth();
        } 
        // If it's a quarter (Q1, Q2, Q3, Q4) and it's based on the organization's start date
        else {
            // Determine the quarter based on the organization's start_date
            $startMonth = $organizationStartDate->month;
    
            // Calculate the start and end dates for the quarter based on the organization's start date
            if ($request->month == 'Q1') {
                // First quarter starts from the organization's start month, within the tax return's year
                $startDate = Carbon::create($request->year, $startMonth, 1); // Start of the first quarter
                $endDate = $startDate->copy()->addMonths(2)->endOfMonth(); // End of the third month
            } else if ($request->month == 'Q2') {
                $startDate = Carbon::create($request->year, $startMonth + 3, 1); // Start of second quarter (3 months after start month)
                $endDate = $startDate->copy()->addMonths(2)->endOfMonth(); // End of the sixth month
            } else if ($request->month == 'Q3') {
                $startDate = Carbon::create($request->year, $startMonth + 6, 1); // Start of third quarter (6 months after start month)
                $endDate = $startDate->copy()->addMonths(2)->endOfMonth(); // End of the ninth month
            } else if ($request->month == 'Q4') {
                $startDate = Carbon::create($request->year, $startMonth + 9, 1); // Start of fourth quarter (9 months after start month)
                $endDate = $startDate->copy()->addMonths(2)->endOfMonth(); // End of the twelfth month
            }
        }
    
        // Step 4: Fetch transactions based on date range and organization_id
        $transactions = Transactions::where('organization_id', $request->organization_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    
        // Step 5: Attach transactions to the tax return only if there are any
        if ($transactions->isNotEmpty()) {
            $taxReturn->transactions()->attach($transactions->pluck('id'));
        }
    
        // Step 6: Return back to the same page with success message
        return back()->with('success', 'Tax Return created successfully.' . ($transactions->isEmpty() ? ' No transactions found for the selected period.' : ' Transactions associated successfully.'));
    }
    public function storePercentage(StoreTaxReturnRequest $request)
    {
        // Step 1: Create the tax return
        $taxReturn = TaxReturn::create([
            'title' => $request->type,
            'year' => $request->year, // This is the tax return's year (from the request)
            'month' => $request->month,
            'created_by' => auth()->id(),
            'organization_id' => $request->organization_id, 
            'status' => 'Unfiled',
        ]);
    
        // Step 2: Get the organization's start date
        $organization = OrgSetup::find($request->organization_id);
        $organizationStartDate = Carbon::parse($organization->start_date); // Assume start_date is a date field
    
        // Step 3: Determine the date range based on the selected month/quarter
        $startDate = null;
        $endDate = null;
    
        // If it's a month selection (1-12)
        if (is_numeric($request->month)) {
            // Use the tax return's year for month calculation
            $startDate = Carbon::create($request->year, $request->month, 1)->startOfMonth();
            $endDate = Carbon::create($request->year, $request->month, 1)->endOfMonth();
        } 
        // If it's a quarter (Q1, Q2, Q3, Q4) and it's based on the organization's start date
        else {
            // Determine the quarter based on the organization's start_date
            $startMonth = $organizationStartDate->month;
    
            // Calculate the start and end dates for the quarter based on the organization's start date
            if ($request->month == 'Q1') {
                // First quarter starts from the organization's start month, within the tax return's year
                $startDate = Carbon::create($request->year, $startMonth, 1); // Start of the first quarter
                $endDate = $startDate->copy()->addMonths(2)->endOfMonth(); // End of the third month
            } else if ($request->month == 'Q2') {
                $startDate = Carbon::create($request->year, $startMonth + 3, 1); // Start of second quarter (3 months after start month)
                $endDate = $startDate->copy()->addMonths(2)->endOfMonth(); // End of the sixth month
            } else if ($request->month == 'Q3') {
                $startDate = Carbon::create($request->year, $startMonth + 6, 1); // Start of third quarter (6 months after start month)
                $endDate = $startDate->copy()->addMonths(2)->endOfMonth(); // End of the ninth month
            } else if ($request->month == 'Q4') {
                $startDate = Carbon::create($request->year, $startMonth + 9, 1); // Start of fourth quarter (9 months after start month)
                $endDate = $startDate->copy()->addMonths(2)->endOfMonth(); // End of the twelfth month
            }
        }
    
        // Step 4: Fetch transactions based on date range and organization_id
        $transactions = Transactions::where('organization_id', $request->organization_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    
        // Step 5: Attach transactions to the tax return only if there are any
        if ($transactions->isNotEmpty()) {
            $taxReturn->transactions()->attach($transactions->pluck('id'));
        }
    
        // Step 6: Return back to the same page with success message
        return back()->with('success', 'Tax Return created successfully.' . ($transactions->isEmpty() ? ' No transactions found for the selected period.' : ' Transactions associated successfully.'));
    }
    
    

    /**
     * Display the specified resource.
     */
 

    public function showSlspData(TaxReturn $taxReturn)
    {
      
        $transactions = $taxReturn->transactions()->with('taxRows')->paginate(10); 

        return view('tax_return.vat_show', compact('taxReturn', 'transactions'));
    }
    
    public function showPercentageSlspData(TaxReturn $taxReturn)
    {
    
        $paginatedTaxRows = TaxRow::whereHas('transaction', function ($query) {
            $query->where('tax_type', 2)
                  ->where('transaction_type', 'sales');
        })->paginate(5);

        return view('tax_return.percentage_show', compact('taxReturn', 'paginatedTaxRows'));
    }
    
    


        public function showReport(TaxReturn $taxReturn)
    {
        // Get the current organization_id from the session
        $organizationId = Session::get('organization_id');
        
        // Retrieve the organization setup for the current organization
        $orgSetup = OrgSetup::with('Rdo')->where('id', $organizationId)->first();

        $fullAddress = "{$orgSetup->address_line}, {$orgSetup->city}, {$orgSetup->province}, {$orgSetup->region} " ?? ' ';
        $ZipCode= $orgSetup->zip_code ?? '';

        $rdoCode = optional($orgSetup->Rdo)->rdo_code ?? '' ;
        $LineOfBusiness=$orgSetup->line_of_business ?? '';
        $TaxpayerName=$orgSetup->registration_name??'';
        $TelephoneNumber=$orgSetup->contact_number ?? '';
        $taxReturnId = $taxReturn->id;

    // Initialize totals for each category
    $totalNetSales = 0; // For 15A
    $totalOutputTax = 0; // For 15B
    $totalGovNetSales = 0;
    $totalGovOutputTax = 0;
    $totalExemptNetSales = 0; // For Exempt Sales
    $totalExemptOutputTax = 0; // This will be zero
    $totalZeroRatedNetSales = 0; // For Zero Rated Sales
    $totalZeroRatedOutputTax = 0; // This will be zero

    // Fetch tax return transactions for all relevant sales
    $taxReturnTransactions = TaxReturnTransaction::with(['taxRows' => function ($query) {
        $query->whereIn('tax_type', [1, 2, 3, 4, 5, 6, 7, 8]); // Include all relevant tax types
    }])
    ->whereHas('transaction', function ($query) {
        $query->where('transaction_type', 'sales'); // Filter for sales transaction type
    })
    ->where('tax_return_id', $taxReturnId)
    ->get();

    // Loop through the fetched transactions and their tax rows to calculate totals
    foreach ($taxReturnTransactions as $transaction) {
        foreach ($transaction->taxRows as $taxRow) {
            // Summing for vatable sales
            if (in_array($taxRow->tax_type, [1, 5])) { // VAT on Sales (Goods/Services)
                $totalNetSales += $taxRow->net_amount; 
                $totalOutputTax += $taxRow->tax_amount; 
            }

            // Summing for sales to government
            elseif (in_array($taxRow->tax_type, [2, 6])) { // Sales to Government (Goods/Services)
                $totalGovNetSales += $taxRow->net_amount; 
                $totalGovOutputTax += $taxRow->tax_amount; 
            }

            // Summing for zero-rated sales
            elseif (in_array($taxRow->tax_type, [3, 7])) { // Zero Rated Sales (Goods/Services)
                $totalZeroRatedNetSales += $taxRow->net_amount; 
                // Output tax for zero-rated sales is always zero
            }

            // Summing for exempt sales
            elseif (in_array($taxRow->tax_type, [4, 8])) { // Tax-Exempt Sales (Goods/Services)
                $totalExemptNetSales += $taxRow->net_amount; 
                // Output tax for exempt sales is always zero
            }
        }
    }

    // Calculate Total Sales/Receipts and Output Tax Due (Line 19)
    $totalSalesAndReceipts = $totalNetSales + $totalZeroRatedNetSales + $totalExemptNetSales + $totalGovNetSales;
    $totalOutputTaxDue = $totalOutputTax + $totalGovOutputTax; // This will be the sum of output tax for vatable and government sales


    // $inputTaxCarriedOver = InputTax::where('tax_return_id', $previousTaxReturnId)->sum('input_tax'); assumed method
    // $deferredInputTax = CapitalGoods::where('tax_return_id', $previousTaxReturnId)
    //                                  ->where('cost', '>', 1000000)
    //                                  ->sum('input_tax'); assumed method
    $inputTaxCarriedOver = 0;
    $deferredInputTax = 0;
    $transitionalInputTax = 0; // Fetch or define this value based on your situation
    $presumptiveInputTax = 0; // Fetch or define this value based on your situation
    $otherInputTax = 0; // Fetch or define this value based on your situation

    // Calculate total allowable input tax (20F)
    $totalAllowableInputTax = $inputTaxCarriedOver + $deferredInputTax + $transitionalInputTax + $presumptiveInputTax + $otherInputTax;

    // Optional: Round if necessary
    $totalAllowableInputTax = round($totalAllowableInputTax);
// Fetch relevant purchase transactions for different tax types
$purchaseReturnTransactions = TaxReturnTransaction::with(['taxRows' => function ($query) {
    $query->whereIn('tax_type', [10, 11, 13, 15, 16, 17, 18]); // Relevant tax types for purchases
}])
->whereHas('transaction', function ($query) {
    $query->where('transaction_type', 'purchase'); // Filter for purchase transaction type
})
->where('tax_return_id', $taxReturnId)
->get();

// Initialize totals
$totalCapitalGoodsUnder1MNet = $totalCapitalGoodsUnder1MTax = 0;
$totalCapitalGoodsOver1MNet = $totalCapitalGoodsOver1MTax = 0;
$totalDomesticGoodsNonCapitalNet = $totalDomesticGoodsNonCapitalTax = 0;
$totalImportedGoodsNonCapitalNet = $totalImportedGoodsNonCapitalTax = 0;
$totalDomesticServicesNet = $totalDomesticServicesTax = 0;
$totalServicesByNonResidentsNet = $totalServicesByNonResidentsTax = 0;
$totalNonQualifiedPurchases = 0;
$totalOtherPurchasesNet = $totalOtherPurchasesTax = 0;

// Loop through each transaction and accumulate values based on tax_type and conditions
foreach ($purchaseReturnTransactions as $transaction) {
foreach ($transaction->taxRows as $taxRow) {
    switch ($taxRow->tax_type) {
        case 11: // Capital Goods
            if ($taxRow->amount <= 1000000) { // Under 1M
                $totalCapitalGoodsUnder1MNet += $taxRow->net_amount;
                $totalCapitalGoodsUnder1MTax += $taxRow->tax_amount;
            } else { // Over 1M
                $totalCapitalGoodsOver1MNet += $taxRow->net_amount;
                $totalCapitalGoodsOver1MTax += $taxRow->tax_amount;
            }
            break;

        case 10: // Domestic Goods Non-Capital
            $totalDomesticGoodsNonCapitalNet += $taxRow->net_amount;
            $totalDomesticGoodsNonCapitalTax += $taxRow->tax_amount;
            break;

        case 13: // Imported Goods Non-Capital
            $totalImportedGoodsNonCapitalNet += $taxRow->net_amount;
            $totalImportedGoodsNonCapitalTax += $taxRow->tax_amount;
            break;

        case 15: // Domestic Services
            $totalDomesticServicesNet += $taxRow->net_amount;
            $totalDomesticServicesTax += $taxRow->tax_amount;
            break;

        case 17: // Services by Non-Residents
            $totalServicesByNonResidentsNet += $taxRow->net_amount;
            $totalServicesByNonResidentsTax += $taxRow->tax_amount;
            break;

        case 16: // Non-Qualified Purchases
            $totalNonQualifiedPurchases += $taxRow->net_amount;
            break;

        case 18: // Other Purchases
            $totalOtherPurchasesNet += $taxRow->net_amount;
            $totalOtherPurchasesTax += $taxRow->tax_amount;
            break;
    }
}
}

// Calculate totals
$totalCurrentPurchasesNet = $totalCapitalGoodsUnder1MNet + $totalCapitalGoodsOver1MNet + $totalDomesticGoodsNonCapitalNet +
                        $totalImportedGoodsNonCapitalNet + $totalDomesticServicesNet + $totalServicesByNonResidentsNet +
                        $totalNonQualifiedPurchases + $totalOtherPurchasesNet;

$totalCurrentPurchasesTax = $totalCapitalGoodsUnder1MTax + $totalCapitalGoodsOver1MTax + $totalDomesticGoodsNonCapitalTax +
                        $totalImportedGoodsNonCapitalTax + $totalDomesticServicesTax + $totalServicesByNonResidentsTax +
                        $totalOtherPurchasesTax;

        // Initialize default fields array
        $fields = [
            'YearC' => '?',
            'YearF' => '?',
            'MMYYYY'=> '?',
            '1st'=> '',
            '2nd'=> '',
            '3rd'=> '',
            '4th'=> '',
            "ReturnFrom"=>'',
            "ReturnTo"=>'',
            "AmendedNo"=>'X',  // Default to 'X' indicating NOT amended
            "AmendedYes"=>'',   // Default to empty
            "ShortNo"=>'X',     // Default to 'X' indicating NOT short period
            "ShortYes"=>'',     // Default to empty
            'RDO'=> $rdoCode,
            'SHEETS'=>'X',
            'LineOfBusiness'=>$LineOfBusiness,
            'TaxpayerName'=> $TaxpayerName,
            'TelephoneNumber'=> $TelephoneNumber,
            'Address'=> $fullAddress,
            'ZipCode'=>$ZipCode,
            'LawYes'=>'',
            'LawYesSpecify'=>'',
            'LawNo'=>'X',
            '15A' => $totalNetSales,
            '15B'=>$totalOutputTax,
            '16A' =>$totalGovNetSales,
            '16B'=>$totalGovOutputTax,
            '17'=>$totalZeroRatedNetSales,
            '18'=>$totalExemptOutputTax,
            '19A'=>$totalSalesAndReceipts,
            '19B'=>$totalOutputTaxDue,
            '20A'=>$inputTaxCarriedOver,
            '20B'=>$deferredInputTax,
            '20C'=>$transitionalInputTax,
            '20D'=>$presumptiveInputTax,
            '20E'=>$otherInputTax,
            '20F'=>$totalAllowableInputTax,
            '21A'=>$totalCapitalGoodsUnder1MNet,
            '21B'=>$totalCapitalGoodsUnder1MTax,
            '21C'=>$totalCapitalGoodsOver1MNet,
            '21D'=>$totalCapitalGoodsOver1MTax,
            '21E'=>$totalDomesticGoodsNonCapitalNet,
            '21F'=>$totalDomesticGoodsNonCapitalTax,
            '21G'=>$totalImportedGoodsNonCapitalNet,
            '21H'=>$totalImportedGoodsNonCapitalTax,
            '21I'=>$totalDomesticServicesNet,
            '21J'=>$totalDomesticServicesTax,
            '21K'=>$totalServicesByNonResidentsNet,
            '21L'=>$totalServicesByNonResidentsTax,
            '21M'=>$totalNonQualifiedPurchases,
            '21N'=>$totalOtherPurchasesNet,
            '21O'=>$totalOtherPurchasesTax,
            '21P'=>$totalCurrentPurchasesNet,
            '22'=>$totalCurrentPurchasesTax,
            '23A'=>'0',
            '23B'=>'0',
            '23C'=>'0',
            '23D'=>'0',
            '23E'=>'0',
            '23F'=>'0',
            '24'=>'0',
            '25'=>'0',
            '26A'=>'0',
            '26B'=>'0',
            '26C'=>'0',
            '26D'=>'0',
            '26E'=>'0',
            '26F'=>'0',
            '26G'=>'0',
            '26H'=>'0',
            '27'=>'0',
            '28A'=>'0',
            '28B'=>'0',
            '28C'=>'0',
            '28D'=>'0',
            '29'=>'0',
        ];

        // Determine Financial Year Type and set YearF or YearC
        if ($orgSetup) {
            $fields['YearF'] = $orgSetup->financial_year_end === 'Fiscal' ? 'X' : '';
            $fields['YearC'] = $orgSetup->financial_year_end === 'Calendar' ? 'X' : '';
            
            // Extract the TIN parts from orgSetup
            $tinParts = explode('-', $orgSetup->tin); // Split TIN based on '-'
            
            // Ensure TIN has exactly 4 parts
            if (count($tinParts) === 4) {
                $fields['TIN1'] = $tinParts[0]; // First part
                $fields['TIN2'] = $tinParts[1]; // Second part
                $fields['TIN3'] = $tinParts[2]; // Third part
                $fields['TIN4'] = $tinParts[3]; // Fourth part
            } else {
                // Handle case where TIN format is invalid (if needed)
                $fields['TIN1'] = $fields['TIN2'] = $fields['TIN3'] = $fields['TIN4'] = ''; // Reset TIN fields if invalid
            }
        }

        // Determine Quarter based on the stored "Q1", "Q2", "Q3", or "Q4" and set Return Period
        $quarter = $taxReturn->month; // Assuming month stores "Q1", "Q2", etc.
        $year = $taxReturn->year ?? date('Y'); // Use year in taxReturn or current year as fallback

        switch ($quarter) {
            case 'Q1':
                $fields['1st'] = 'X';
                $fields['MMYYYY'] = '03' .'/'. $year;
                $fields['ReturnFrom'] = '01/01/' . $year;
                $fields['ReturnTo'] = '03/31/' . $year;
                break;
            case 'Q2':
                $fields['2nd'] = 'X';
                $fields['MMYYYY'] = '06' .'/'. $year;
                $fields['ReturnFrom'] = '04/01/' . $year;
                $fields['ReturnTo'] = '06/30/' . $year;
                break;
            case 'Q3':
                $fields['3rd'] = 'X';
                $fields['MMYYYY'] = '09' .'/'. $year;
                $fields['ReturnFrom'] = '07/01/' . $year;
                $fields['ReturnTo'] = '09/30/' . $year;
                break;
            case 'Q4':
                $fields['4th'] = 'X';
                $fields['MMYYYY'] = '12' .'/'. $year;
                $fields['ReturnFrom'] = '10/01/' . $year;
                $fields['ReturnTo'] = '12/31/' . $year;
                break;
            default:
                // Default to Q1 if no valid quarter is found
                $fields['ReturnFrom'] = '01/01/' . $year;
                $fields['ReturnTo'] = '03/31/' . $year;
                break;
        }

        // Determine if the return is amended based on user input
        if ($taxReturn->is_amended) { // Assuming is_amended is set based on user input
            $fields['AmendedNo'] = ''; // Clear No
            $fields['AmendedYes'] = 'X'; // Set Yes
        }

        // Determine if the return is a short period return
        if ($taxReturn->is_short_period) { // Assuming is_short_period is set based on user input
            $fields['ShortNo'] = ''; // Clear No
            $fields['ShortYes'] = 'X'; // Set Yes
        }

        // Path to the editable PDF located in the public/pdfs directory
        $pdfTemplatePath = public_path('pdfs/2550QS_EDITABLE.pdf');

        // Check if the PDF template exists
        if (!file_exists($pdfTemplatePath)) {
            return dd('PDF template not found at: ' . $pdfTemplatePath);
        }

        // Create a new PDF instance
        $pdf = new \mikehaertl\pdftk\Pdf($pdfTemplatePath);

        // Fill the PDF with the data
        $result = $pdf->fillForm($fields)
            ->needAppearances()
            ->saveAs(storage_path('app/public/filled_report.pdf'));

        // Check for errors
        if (!$result) {
            Log::error('PDF fill form error: ' . $pdf->getError());
            return dd('PDF fill form failed: ' . $pdf->getError());
        }

        // Check if the output PDF was created successfully
        $outputPdfPath = storage_path('app/public/filled_report.pdf');
        if (!file_exists($outputPdfPath)) {
            return dd('PDF was not created at: ' . $outputPdfPath);
        }

        // Pass the PDF path to the view for display
        return view('tax_return.vat_report', [
            'taxReturn' => $taxReturn,
            'pdfPath' => asset('storage/filled_report.pdf'), // Serve the PDF from public storage
        ]);
    }

    


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxReturn $taxReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxReturnRequest $request, TaxReturn $taxReturn)
    {
        //
    }

    //Soft delete
    public function destroy($id)
    {
        $taxreturn = TaxReturn::findOrFail($id);
        $taxreturn->delete(); // Soft delete the transaction

        return back()->with('success', 'Tax Return deleted successfully!');
    }
}

