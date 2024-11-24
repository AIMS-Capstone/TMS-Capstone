<?php

namespace App\Http\Controllers;

use App\Models\Tax2551QSchedule;
use App\Models\TaxReturn;
use App\Http\Requests\StoreTaxReturnRequest;
use App\Http\Requests\UpdateTaxReturnRequest;
use App\Models\OrgSetup;
use App\Models\Tax2551Q;
use App\Models\TaxReturnTransaction;
use App\Models\TaxRow;
use App\Models\Transactions;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use mikehaertl\pdftk\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use FPDM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

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

    $rdoCode = optional($organization->Rdo)->rdo_code ?? '';

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
    $period = ($yearEnded->month == 12 && $yearEnded->day == 31) ? 'calendar' : 'fiscal';

    // Format the year ended date as 'YYYY-MM'
    $yearEndedFormatted = $yearEnded->format('Y-m');
    $yearEndedFormattedForDisplay = $yearEnded->format('m/Y'); // e.g., "12/2024"

    // Get the transaction IDs related to this tax return
    $transactionIds = $taxReturn->transactions->pluck('id');

    // Get the TaxRows and calculate the summary data
    $taxRows = TaxRow::whereHas('transaction', function ($query) use ($transactionIds) {
        $query->where('tax_type', 2)
              ->where('transaction_type', 'sales')
              ->where('status', 'draft')
              ->whereIn('id', $transactionIds);
    })->with(['transaction.contactDetails', 'atc', 'taxType'])
      ->get();

    $totalZeroRatedSales ="";
    // Group the TaxRows by ATC and calculate the summary       
    $groupedData = $taxRows->groupBy(function ($taxRow) {
        return $taxRow->atc->tax_code; // Group by ATC code
    });

    $summaryData = $groupedData->map(function ($group) {
        $taxableAmount = 0;
        $taxRate = 0;
        $taxDue = 0;

        foreach ($group as $taxRow) {
            $taxableAmount += $taxRow->net_amount;
            $taxRate = $taxRow->atc->tax_rate;
            $taxDue += $taxRow->atc_amount;
        }

        return [
            'taxable_amount' => $taxableAmount,
            'tax_rate' => $taxRate,
            'tax_due' => $taxDue,
        ];
    });

    // Pass the summary data to the view
    return view('tax_return.percentage_report_preview', compact(
        'taxReturn',
        'organization',
        'yearEndedFormatted',
        'yearEndedFormattedForDisplay',
        'period',
        'rdoCode',
        'summaryData' // Add the summary data to the view
    ));
}
public function showVatReport($id)
{
    // Fetch the tax return and organization setup data
    $taxReturn = TaxReturn::findOrFail($id);
    $organization_id = session("organization_id");

    // Load the organization and its RDO relationship
    $organization = OrgSetup::with("rdo")
        ->where('id', $organization_id)
        ->first();

    // Extract the RDO code from the organization data
    $rdoCode = optional($organization->Rdo)->rdo_code ?? '';

    // Parse the organization's start date to determine the fiscal year
    $startDate = Carbon::parse($organization->start_date);
    $yearEnded = $startDate->month == 1 && $startDate->day == 1
        ? $startDate->addYear()->endOfYear()  // Calendar year end
        : $startDate->addYear()->lastOfMonth(); // Fiscal year end

    // Determine whether it's a calendar or fiscal year
    $period = $yearEnded->month == 12 && $yearEnded->day == 31 ? 'calendar' : 'fiscal';
    $yearEndedFormatted = $yearEnded->format('Y-m');
    $yearEndedFormattedForDisplay = $yearEnded->format('m/Y');

    // Retrieve current quarter and year
    $currentQuarter = (int)filter_var($taxReturn->month, FILTER_SANITIZE_NUMBER_INT); // Extract quarter number
    $currentYear = $taxReturn->year;

    // Initialize previous quarter and year
    $previousQuarter = null;
    $previousYear = null;

    // Determine the previous quarter if applicable
    if ($currentQuarter > 1) {
        $previousQuarter = $currentQuarter - 1;
        $previousYear = $currentYear;
    }

    // Initialize excess input tax for previous quarter (if applicable)
    $excessInputTax = null;
    if ($previousQuarter && $previousYear) {
        $previousTaxReturn = TaxReturn::where('year', $previousYear)
            ->where('month', $previousQuarter)
            ->where('organization_id', $organization_id)
            ->first();

        if ($previousTaxReturn) {
            $excessInputTax = $previousTaxReturn->related2550q->excess_input_tax ?? null;
        }
    }

    // Retrieve the transaction IDs linked to this tax return
    $transactionIds = $taxReturn->transactions->pluck('id');

    // Get the TaxRows and load necessary relationships
    $taxRows = TaxRow::whereHas('transaction', function ($query) use ($transactionIds) {
        $query->where('tax_type', '!=', 2)
              ->where('status', 'draft')
              ->whereIn('id', $transactionIds);
    })->with(['transaction.contactDetails', 'atc', 'taxType'])->get();

    // Initialize variables for each tax type and tax amount
    $vatOnSalesGoods = $vatOnSalesServices = $salesToGovernmentGoods = $salesToGovernmentServices = 0;
    $zeroRatedSalesGoods = $zeroRatedSalesServices = $taxExemptSalesGoods = $taxExemptSalesServices = 0;
    $vatOnPurchasesGoods = $vatOnPurchasesServices = $capitalGoods = $importationOfGoods = 0;
    $taxExemptPurchasesImportationOfGoods = 0;
    $goodsNotQualifiedForInputTax = $servicesNotQualifiedForInputTax = $servicesByNonResidents = $nonTaxSales = $nonTaxPurchases = 0;

    $vatOnSalesGoodsTax = $vatOnSalesServicesTax = $salesToGovernmentGoodsTax = $salesToGovernmentServicesTax = 0;
    $zeroRatedSalesGoodsTax = $zeroRatedSalesServicesTax = $taxExemptSalesGoodsTax = $taxExemptSalesServicesTax = 0;
    $vatOnPurchasesGoodsTax = $vatOnPurchasesServicesTax = $capitalGoodsTax = $importationOfGoodsTax = 0;
    $taxExemptPurchasesImportationOfGoodsTax = $goodsNotQualifiedForInputTaxTax = $servicesNotQualifiedForInputTaxTax = $servicesByNonResidentsTax = 0;

    // Calculate totals for each tax type
    foreach ($taxRows as $taxRow) {
        $taxType = $taxRow->taxType;

        if ($taxType) {
            switch ($taxType->tax_type) {
                case 'Vat on Sales (Goods)':
                    $vatOnSalesGoods += $taxRow->net_amount;
                    $vatOnSalesGoodsTax += $taxRow->tax_amount;
                    break;
                case 'Vat on Sales (Services)':
                    $vatOnSalesServices += $taxRow->net_amount;
                    $vatOnSalesServicesTax += $taxRow->tax_amount;
                    break;
                case 'Sales to Government (Goods)':
                    $salesToGovernmentGoods += $taxRow->net_amount;
                    $salesToGovernmentGoodsTax += $taxRow->tax_amount;
                    break;
                case 'Sales to Government (Services)':
                    $salesToGovernmentServices += $taxRow->net_amount;
                    $salesToGovernmentServicesTax += $taxRow->tax_amount;
                    break;
                case 'Zero Rated Sales (Goods)':
                    $zeroRatedSalesGoods += $taxRow->net_amount;
                    $zeroRatedSalesGoodsTax += $taxRow->tax_amount;
                    break;
                case 'Zero Rated Sales (Services)':
                    $zeroRatedSalesServices += $taxRow->net_amount;
                    $zeroRatedSalesServicesTax += $taxRow->tax_amount;
                    break;
                case 'Tax-Exempt Sales (Goods)':
                    $taxExemptSalesGoods += $taxRow->net_amount;
                    $taxExemptSalesGoodsTax += $taxRow->tax_amount;
                    break;
                case 'Tax-Exempt Sales (Services)':
                    $taxExemptSalesServices += $taxRow->net_amount;
                    $taxExemptSalesServicesTax += $taxRow->tax_amount;
                    break;
                case 'Non-Tax':
                    // Distinguish between Non-Tax Sales and Purchases
                    if ($taxRow->transaction->transaction_type == 'purchase') {
                        $nonTaxPurchases += $taxRow->net_amount;
                    } else {
                        $nonTaxSales += $taxRow->net_amount;
                    }
                    break;
                case 'Vat on Purchases (Goods)':
                    $vatOnPurchasesGoods += $taxRow->net_amount;
                    $vatOnPurchasesGoodsTax += $taxRow->tax_amount;
                    break;
                case 'Vat on Purchases (Services)':
                    $vatOnPurchasesServices += $taxRow->net_amount;
                    $vatOnPurchasesServicesTax += $taxRow->tax_amount;
                    break;
                case 'Capital Goods':
                    $capitalGoods += $taxRow->net_amount;
                    $capitalGoodsTax += $taxRow->tax_amount;
                    break;
                case 'Goods Not Qualified for Input Tax':
                    $goodsNotQualifiedForInputTax += $taxRow->net_amount;
                    $goodsNotQualifiedForInputTaxTax += $taxRow->tax_amount;
                    break;
                case 'Importation of Goods':
                    $importationOfGoods += $taxRow->net_amount;
                    $importationOfGoodsTax += $taxRow->tax_amount;
                    break;
                case 'Tax-Exempt Purchases (Importation of Goods)':
                    $taxExemptPurchasesImportationOfGoods += $taxRow->net_amount;
                    $taxExemptPurchasesImportationOfGoodsTax += $taxRow->tax_amount;
                    break;
                case 'Services Not Qualified for Input Tax':
                    $servicesNotQualifiedForInputTax += $taxRow->net_amount;
                    $servicesNotQualifiedForInputTaxTax += $taxRow->tax_amount;
                    break;
                case 'Services by Non-Residents':
                    $servicesByNonResidents += $taxRow->net_amount;
                    $servicesByNonResidentsTax += $taxRow->tax_amount;
                    break;
            }
        }
    }

    // Pass all required variables to the view
    return view('tax_return.vat_report_preview', compact(
        'vatOnSalesGoods', 'vatOnSalesGoodsTax',
        'vatOnSalesServices', 'vatOnSalesServicesTax',
        'salesToGovernmentGoods', 'salesToGovernmentGoodsTax',
        'salesToGovernmentServices', 'salesToGovernmentServicesTax',
        'zeroRatedSalesGoods', 'zeroRatedSalesGoodsTax',
        'zeroRatedSalesServices', 'zeroRatedSalesServicesTax',
        'taxExemptSalesGoods', 'taxExemptSalesGoodsTax',
        'taxExemptSalesServices', 'taxExemptSalesServicesTax',
        'nonTaxSales', 'nonTaxPurchases',
        'vatOnPurchasesGoods', 'vatOnPurchasesGoodsTax',
        'vatOnPurchasesServices', 'vatOnPurchasesServicesTax',
        'capitalGoods', 'capitalGoodsTax',
        'importationOfGoods', 'importationOfGoodsTax',
        'taxExemptPurchasesImportationOfGoods', 'taxExemptPurchasesImportationOfGoodsTax',
        'goodsNotQualifiedForInputTax', 'goodsNotQualifiedForInputTaxTax',
        'servicesNotQualifiedForInputTax', 'servicesNotQualifiedForInputTaxTax',
        'servicesByNonResidents', 'servicesByNonResidentsTax',
        'rdoCode', 'period', 'yearEndedFormatted', 'taxReturn', 'organization',
        'yearEndedFormattedForDisplay', 'currentQuarter', 'previousQuarter',
        'previousYear', 'currentYear'
    ));
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

     public function store2551Q(Request $request, $taxReturn)
     {
         // Validate the incoming request data
         $validatedData = $request->validate([
             'period' => 'required|string',
             'year_ended' => 'required|date',
             'quarter' => 'required|string',
             'amended_return' => 'required|string',
             'sheets_attached' => 'required|integer',
             'tin' => 'required|string',
             'rdo_code' => 'required|string',
             'taxpayer_name' => 'required|string',
             'registered_address' => 'required|string',
             'zip_code' => 'required|string',
             'contact_number' => 'required|string',
             'email_address' => 'required|email',
             'tax_relief' => 'required|string',
             'yes_specify' => 'nullable|string',
             'availed_tax_rate' => 'required|string',
             'tax_due' => 'required|numeric',
             'creditable_tax' => 'required|numeric',
             'amended_tax' => 'nullable|numeric',
             'other_tax_specify' => 'nullable|string',
             'other_tax_amount' => 'nullable|numeric',
             'total_tax_credits' => 'required|numeric',
             'tax_still_payable' => 'required|numeric',
             'surcharge' => 'nullable|numeric',
             'interest' => 'nullable|numeric',
             'compromise' => 'nullable|numeric',
             'total_penalties' => 'nullable|numeric',
             'total_amount_payable' => 'required|numeric',
             'schedule' => 'nullable|array', // Ensure 'schedule' is an array
         ]);
         
         // Add the tax_return_id to the validated data before creating/updating the record
         $validatedData['tax_return_id'] = $taxReturn;
         
         
         // Use updateOrCreate to either update the existing Tax2551Q or create a new one
         $tax2551Q = Tax2551Q::updateOrCreate(
             ['tax_return_id' => $taxReturn], // Search for a record with the same tax_return_id
             $validatedData // Update with the new validated data
         );
         
         // Check if 'schedule' data exists and process it
         if (isset($validatedData['schedule']) && !empty($validatedData['schedule'])) {
             foreach ($validatedData['schedule'] as $scheduleData) {
                // Clean and validate tax_base for each schedule item
              
            $scheduleData['taxable_amount'] = preg_replace('/[^0-9.]/', '', $scheduleData['taxable_amount']); // Remove commas
            $scheduleData['taxable_amount'] = (float) $scheduleData['taxable_amount']; // Ensure it's a valid number
                 // Check if a schedule entry for the same tax_return_id already exists
                 Tax2551QSchedule::updateOrCreate(
                     [
                         '2551q_id' => $tax2551Q->id, // Use the ID of the newly created or updated Tax2551Q
                         'atc_code' => $scheduleData['atc_code'], // Assuming ATC code is unique
                     ],
                     [
                         'tax_base' => $scheduleData['taxable_amount'],
                         'tax_rate' => $scheduleData['tax_rate'],
                         'tax_due' => $scheduleData['tax_due'],
                     ]
                 );
             }
         }
         
         // Redirect back or to a specific page with a success message
         return redirect()->route('tax_return.2551q.pdf', ['taxReturn' => $taxReturn])
        ->with('success', 'Tax return successfully submitted and PDF generated.');
     }
     public function showSlspData(TaxReturn $taxReturn, Request $request)
     {
         // Retrieve the selected type from the request
         $type = $request->get('type', 'sales'); // Default to 'sales' if no type is provided
     
         // Get the transaction IDs linked to this tax return
         $transactionIds = $taxReturn->transactions->pluck('id');
     
         // Define the query
         $paginatedTaxRows = TaxRow::where(function ($query) use ($type) {
             if ($type === 'capital_goods') {
                 // Filter for Capital Goods
                 $query->whereHas('taxType', function ($q) {
                     $q->where('tax_type', 'Capital Goods');
                 });
             } elseif ($type === 'importation') {
                 // Filter for Importation of Goods
                 $query->whereHas('taxType', function ($q) {
                     $q->where('tax_type', 'Importation of Goods');
                 });
             } elseif ($type === 'sales') {
                 // Filter for Sales
                 $query->whereHas('transaction', function ($q) {
                     $q->where('transaction_type', 'sales');
                 })->whereHas('taxType', function ($q) {
                     $q->where('tax_type', '!=', 'Percentage Tax'); // Exclude Percentage Tax
                 });
             } elseif ($type === 'purchases') {
                 // Filter for Purchases
                 $query->whereHas('transaction', function ($q) {
                     $q->where('transaction_type', 'purchase');
                 })->whereDoesntHave('taxType', function ($q) {
                     $q->whereIn('tax_type', ['Capital Goods', 'Importation of Goods']); // Exclude Capital Goods and Importation
                 });
             }
         })
         ->whereHas('transaction', function ($query) use ($transactionIds) {
             $query->whereIn('id', $transactionIds); // Ensure transactions belong to the current tax return
         })
         ->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount']) // Eager load relationships
         ->paginate(5);
     
         return view('tax_return.vat_show', compact('taxReturn', 'paginatedTaxRows', 'type'));
     }
     
     
     public function showSummary(TaxReturn $taxReturn, Request $request)
     {
         // Get the transaction IDs linked to this tax return
         $transactionIds = $taxReturn->transactions->pluck('id');
     
         // Get the tax rows and eager load taxType to access tax_rate and transaction_type
         $taxRows = TaxRow::whereIn('transaction_id', $transactionIds)
             ->with('taxType') // Eager load taxType to get transaction_type and tax_rate
             ->get();
     
         // Initialize an array to store the summary data, grouped by tax type
         $summaryData = [];
     
         foreach ($taxRows as $taxRow) {
             $taxType = $taxRow->taxType; // Get the tax type relationship
             $transactionType = $taxType->transaction_type; // Get transaction_type from taxType
     
             // Only process rows with a valid transaction type and tax type
             if ($taxType && $transactionType) {
                 // Get the tax rate from the taxType
                 $tax_rate = $taxType->VAT;
     
                 // Initialize the tax type if not already present in the summaryData
                 if (!isset($summaryData[$taxType->tax_type])) {
                     $summaryData[$taxType->tax_type] = [
                         'net_amount' => 0,
                         'tax_amount' => 0,
                         'tax_rate' => $tax_rate,
                         'transaction_type' => $transactionType, // Store transaction type for later use
                     ];
                 }
     
                 // Aggregate the amounts based on the tax type
                 $summaryData[$taxType->tax_type]['net_amount'] += $taxRow->net_amount;
                 $summaryData[$taxType->tax_type]['tax_amount'] += $taxRow->tax_amount;
             }
         }
     
         // Convert the summary data into a collection
         $summaryCollection = collect($summaryData)->map(function ($item, $key) {
             return [
                 'tax_type' => $key,
                 'vatable_sales' => $item['net_amount'],
                 'tax_due' => $item['tax_amount'],
                 'tax_rate' => $item['tax_rate'],
             ];
         });
     
         // Paginate the summary data
         $perPage = 5; // Number of items per page
         $currentPage = LengthAwarePaginator::resolveCurrentPage();
         $currentItems = $summaryCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
     
         // Create the LengthAwarePaginator instance
         $paginatedSummaryData = new LengthAwarePaginator(
             $currentItems,
             $summaryCollection->count(),
             $perPage,
             $currentPage,
             ['path' => LengthAwarePaginator::resolveCurrentPath()]
         );
     
         // Return the view with the paginated summary data
         return view('tax_return.vat_summary', compact('taxReturn', 'paginatedSummaryData'));
     }
     
     
    
    public function showPercentageSlspData(TaxReturn $taxReturn)
    {
        // Get the transaction IDs that are linked to this tax return
        $transactionIds = $taxReturn->transactions->pluck('id'); // Get all the transaction IDs related to the given tax return
    
        $paginatedTaxRows = TaxRow::whereHas('transaction', function ($query) use ($transactionIds) {
            $query->where('tax_type', 2)
                  ->where('transaction_type', 'sales')
                  ->where('status','draft')
                  ->whereIn('id', $transactionIds); // Only include transactions that belong to this tax return
        })->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount'])
          ->paginate(5);
    
        return view('tax_return.percentage_show', compact('taxReturn', 'paginatedTaxRows'));
    }
    
    
    public function showPercentageSummaryPage(TaxReturn $taxReturn)
{
    // Get the transaction IDs that are linked to this tax return
    $transactionIds = $taxReturn->transactions->pluck('id'); // Get all the transaction IDs related to the given tax return

    // Get the paginated TaxRows with the necessary eager-loaded relationships
    $paginatedTaxRows = TaxRow::whereHas('transaction', function ($query) use ($transactionIds) {
        $query->where('tax_type', 2)
              ->where('transaction_type', 'sales')
              ->where('status', 'draft')
              ->whereIn('id', $transactionIds); // Only include transactions that belong to this tax return
    })->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount'])
      ->paginate(5);

    // Group the TaxRows by their ATC (Account Type Code)
    $groupedData = $paginatedTaxRows->groupBy(function ($taxRow) {
        return $taxRow->atc->tax_code; // Group by ATC code
    });

    // Calculate the summary data for each ATC
    $summaryData = $groupedData->map(function ($group) {
        // Initialize variables to calculate total taxable amount, tax rate, and tax due
        $taxableAmount = 0;
        $taxRate = 0;
        $taxDue = 0;

        // Loop through each row in the group to calculate the totals
        foreach ($group as $taxRow) {
            $taxableAmount += $taxRow->net_amount;  // Sum of all taxable amounts
            $taxRate = $taxRow->atc->tax_rate;     // Get the tax rate from the ATC for this row
            $taxDue += $taxRow->atc_amount;        // Sum of all tax amounts
        }

        return [
            'taxable_amount' => $taxableAmount,
            'tax_rate' => $taxRate,
            'tax_due' => $taxDue,
        ];
    });

    // Paginate the summary data by converting it into a LengthAwarePaginator
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 5;  // Define how many records you want per page

    // Slice the collection to get the data for the current page
    $currentPageData = $summaryData->slice(($currentPage - 1) * $perPage, $perPage)->all();

    // Create a LengthAwarePaginator instance
    $paginatedSummaryData = new LengthAwarePaginator(
        $currentPageData,
        $summaryData->count(),
        $perPage,
        $currentPage,
        ['path' => LengthAwarePaginator::resolveCurrentPath()]
    );

    // Return the view with the paginated TaxRows and the summary data
    return view('tax_return.percentage_summary', compact('taxReturn', 'paginatedTaxRows', 'paginatedSummaryData'));
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
    public function showPercentageReportPDF(TaxReturn $taxReturn)
    {
        // Get data from the `2551q` table
        $formData = Tax2551Q::where('tax_return_id', $taxReturn->id)->first();
        if (!$formData) {
            return dd('No data found in the 2551q table for this tax return.');
        }
    
        // Get schedules via the relationship
        $scheduleData = $formData->schedule1;
        
    
        // Initialize fields array
        $fields = [
            'YearF' => $formData->year_ended === '2021-12' ? 'X' : '',
            'YearC' => $formData->year_ended === '2021-12' ? '' : 'X',
           'MM/YYYY' => implode('/', array_reverse(explode('-', $formData->year_ended))),
            '1stQtr' => $formData->quarter === '1st' ? 'X' : '',
            '2ndQtr' => $formData->quarter === '2nd' ? 'X' : '',
            '3rdQtr' => $formData->quarter === '3rd' ? 'X' : '',
            '4thQtr' => $formData->quarter === '4th' ? 'X' : '',
            'amended_yes' => $formData->is_amended ? 'X' : '',
            'amended_no' => !$formData->is_amended ? 'X' : '',
            'sheets' => $formData->sheets_attached ?? '',
            'TIN1' => explode('-', $formData->tin)[0] ?? '',
            'TIN2' => explode('-', $formData->tin)[1] ?? '',
            'TIN3' => explode('-', $formData->tin)[2] ?? '',
            'TIN4' => explode('-', $formData->tin)[3] ?? '',
            'RDO' => $formData->rdo_code ?? '',
            'TaxpayerName' => $formData->taxpayer_name ?? '',
            'address' => $formData->registered_address ?? '',
            'Zip' => $formData->zip_code ?? '',
            'PhoneNumber' => $formData->contact_number ?? '',
            'Email' => $formData->email_address ?? '',
            'relief_yes' => $formData->tax_relief === 'yes' ? 'X' : '',
            'relief_no' => !$formData->tax_relief === 'no' ? 'X' : '',
            'yes_specify' => $formData->yes_specify ?? '',
            'graduated' => $formData->availed_tax_rate === 'Graduated' ? 'X' : '',
            'flat_rate' => $formData->availed_tax_rate === 'Flat_rate' ? 'X' : '',
            '14' => floor($formData->tax_due ?? 0),
            '14_decimal' => ($formData->tax_due ?? 0) * 100 % 100,
            '15' => floor($formData->creditable_tax ?? 0),
            '15_decimal' => ($formData->creditable_tax ?? 0) * 100 % 100,
            '16' => floor($formData->amended_tax ?? 0),
            '16_decimal' => ($formData->amended_tax ?? 0) * 100 % 100,
            'other_specify' => $formData->other_tax_specify ?? '',
            'other_specify_amount' => floor($formData->other_tax_amount ?? 0),
            'other_specify_decimal' => ($formData->other_tax_amount ?? 0) * 100 % 100,
            '18' => floor($formData->total_tax_credits ?? 0),
            '18_decimal' => ($formData->total_tax_credits ?? 0) * 100 % 100,
            '19' => floor($formData->tax_still_payable ?? 0),
            '19_decimal' => ($formData->tax_still_payable ?? 0) * 100 % 100,
            '20' => floor($formData->surcharge ?? 0),
            '20_decimal' => ($formData->surcharge ?? 0) * 100 % 100,
            '21' => floor($formData->interest ?? 0),
            '21_decimal' => ($formData->interest ?? 0) * 100 % 100,
            '22' => floor($formData->compromise ?? 0),
            '22_decimal' => ($formData->compromise ?? 0) * 100 % 100,
            '23' => floor($formData->total_penalties ?? 0),
            '23_decimal' => ($formData->total_penalties ?? 0) * 100 % 100,
            '24' => floor($formData->total_amount_payable ?? 0),
            '24_decimal' => ($formData->total_amount_payable ?? 0) * 100 % 100,
        ];
    
        // Process schedule data for ATC codes and amounts
        foreach ($scheduleData as $index => $schedule) {
            $atcIndex = $index + 1; // Use 1-based index for ATC fields
            if ($atcIndex > 6) break; // Stop if more than 6 ATCs
    
            $fields["ATC{$atcIndex}"] = $schedule->atc_code ?? '';
            $fields["TaxableAmount{$atcIndex}"] = floor($schedule->tax_base ?? 0);
            $fields["TaxableAmount_decimal{$atcIndex}"] = ($schedule->tax_base ?? 0) * 100 % 100; // Extract decimals
            $fields["TaxRate{$atcIndex}"] = isset($schedule->tax_rate) ? intval($schedule->tax_rate) : '';
            $fields["TaxDue{$atcIndex}"] = floor($schedule->tax_due ?? 0);
            $fields["TaxDue_decimal{$atcIndex}"] = ($schedule->tax_due ?? 0) * 100 % 100;
        }
    
        // Calculate total tax due
        $fields['TotalTaxDue'] = floor($formData->tax_due ?? 0);
        $fields['TotalTaxDue_decimal'] = ($formData->tax_due ?? 0) * 100 % 100;
    
        // Define the PDF template path
        $pdfTemplatePath = public_path('pdfs/2551Q_Editable.pdf');
    
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
    
        // Serve the filled PDF in a view
        return view('tax_return.percentage_report', [
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

