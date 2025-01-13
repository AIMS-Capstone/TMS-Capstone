<?php

namespace App\Http\Controllers;

use App\Models\Tax2551QSchedule;
use App\Models\TaxReturn;
use App\Http\Requests\StoreTaxReturnRequest;
use App\Http\Requests\UpdateTaxReturnRequest;
use App\Models\OrgSetup;
use App\Models\Tax1701Q;
use App\Models\Tax1702Q;
use App\Models\Tax2550Q;
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
use Illuminate\Support\Facades\DB;

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
// Function for showing 2550Q Returns Table
    public function vatReturn()
    {
        $organizationId = session('organization_id');
        $taxReturns = TaxReturn::with('user')
            ->where('organization_id', $organizationId)
            ->whereIn('title', ['2550Q', '2550M'])
            ->get();

        return view('tax_return.vat_return', compact('taxReturns'));
    }
    // Function for showing Income Returns Table
    public function incomeReturn()
    {
        $organizationId = session('organization_id');
        
        // Capture the 'type' query parameter to apply the filter
        $filterType = request()->query('type', '1701Q');  // Default to '1701Q' if no type is passed
        $searchTerm = request()->query('search', '');  // Get the search query parameter, default to empty
        
        // Start the query for tax returns
        $taxReturns = TaxReturn::with('user')  // Make sure to eager load the user relation
            ->where('organization_id', $organizationId)
            ->whereIn('title', ['1701Q', '1702Q', '1701', '1702RT', '1702MX', '1702EX']);
        
           
        // Apply filter if a specific type is selected
        if ($filterType) {
            $taxReturns->where('title', $filterType);
        }
    
        // Apply the search filter if a search term is provided
        if ($searchTerm) {
            $taxReturns->where(function($query) use ($searchTerm) {
                // Search on multiple fields
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('year', 'like', '%' . $searchTerm . '%')
                      ->orWhere('month', 'like', '%' . $searchTerm . '%')
                      ->orWhere('status', 'like', '%' . $searchTerm . '%')
                      ->orWhereDate('created_at', 'like', '%' . $searchTerm . '%');
            });
        }
    
        // Get the filtered tax returns
        $taxReturns = $taxReturns->get();  // Execute the query
        
        // Return the view with the filtered tax returns and other data
        return view('tax_return.income_return', compact('taxReturns', 'filterType', 'searchTerm'));
    }
    
// Function for showing detailed view of Income Returns
public function showIncome($id, $type)
{
    // Retrieve the tax return by its ID
    $taxReturn = TaxReturn::findOrFail($id);
    $organization_id = session("organization_id");

    // Check if the 'type' (or title) matches '1701Q'
    if ($taxReturn->title === '1701Q') {
        // If title is 1701Q, show the specific view for 1701Q
        return view('tax_return.income_input_summary', compact('taxReturn'));
    }

    if ($taxReturn->title === '1702Q') {
        // Check if an existing 1702Q entry exists for this tax return
        $existing1702q = Tax1702Q::where('tax_return_id', $taxReturn->id)->first();

        if ($existing1702q) {
            // Redirect to the PDF route if the 1702Q entry exists
            return redirect()->route('tax_return.corporate_quarterly_pdf', ['taxReturn' => $taxReturn]);
        }

        // If no existing 1702Q entry, proceed with the view preparation
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
        $taxRows = TaxRow::whereHas('transaction', function ($query) use ($transactionIds) {
            $query->where('tax_type', 2)
                  ->where('transaction_type', 'sales')
                  ->where('status', 'draft')
                  ->whereIn('id', $transactionIds);
        })->with(['transaction.contactDetails', 'atc', 'taxType'])
          ->get();

        return view('1702q.preview', compact(
            'taxReturn',
            'organization',
            'yearEndedFormatted',
            'yearEndedFormattedForDisplay',
            'period',
            'rdoCode',
        ));
    }

    // If title is not 1701Q or 1702Q, show a default view
    return view('tax_return.show', compact('taxReturn'));
}


    
    // Function for showing Percentage Report Preview
    public function showPercentageReport($id)
    {
        $taxReturn = TaxReturn::findOrFail($id);
        
        // Check if 2551q form already exists for this tax return
        $existing2551q = Tax2551Q::where('tax_return_id', $taxReturn->id)->first();
        
        if ($existing2551q) {
            // If form exists, redirect to the PDF viewer
            return redirect()->route('tax_return.2551q.pdf', ['taxReturn' => $taxReturn->id]);
        }
        
        // If no form exists, continue with preview logic
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
        $yearEndedFormattedForDisplay = $yearEnded->format('m/Y');
    
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
    
        $totalZeroRatedSales = "";
        
        // Group the TaxRows by ATC and calculate the summary       
        $groupedData = $taxRows->groupBy(function ($taxRow) {
            return $taxRow->atc->tax_code;
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
            'summaryData'
        ));
    }

    public function markAsFiled($id)
    {
        $taxReturn = TaxReturn::findOrFail($id);
        
        // Check if the current status is not already "Filed"
        if ($taxReturn->status === 'Filed') {
            return redirect()->back()->with('error', 'This tax return is already marked as Filed.');
        }
        
        // Update the status to "Filed"
        $taxReturn->status = 'Filed';
        $taxReturn->save();
        
        return redirect()->back()->with('success', 'Tax return has been marked as Filed.');

    }
  // Function for showing Value Added Tax Report Preview
public function showVatReport($id)
{
    // Fetch the tax return and organization setup data
    $taxReturn = TaxReturn::findOrFail($id);
    $organization_id = session("organization_id");

    // Load the organization and its RDO relationship
    $organization = OrgSetup::with("rdo")
        ->where('id', $organization_id)
        ->first();

        $existing2550q = Tax2550Q::where('tax_return_id', $taxReturn->id)->first();
        if ($existing2550q) {
            // If a Tax2550q is already available, redirect to the PDF report
            return redirect()->route('tax_return.2550q.pdf', ['taxReturn' => $taxReturn->id]);
        }
    // Extract the RDO code from the organization data
    $rdoCode = optional($organization->Rdo)->rdo_code ?? '';

    // Parse the organization's start date to determine the fiscal year
    $startDate = Carbon::parse($organization->start_date);
    $taxYear = Carbon::parse($taxReturn->year);
    
    // Use the month from the organization's start date and the year from the tax return
    $yearEnded = Carbon::createFromDate($taxYear->year, $startDate->month, $startDate->day);
    
    // Determine the correct end of the period
    $yearEnded = $yearEnded->month == 1 && $yearEnded->day == 1
        ? $yearEnded->addYear()->endOfYear()  // Calendar year end
        : $yearEnded->addYear()->lastOfMonth(); // Fiscal year end
    
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
  // Function for showing Income Tax (1701Q) Report Preview
  public function showIncomeReport($id)
  {
      // Fetch the tax return and organization setup data
      $taxReturn = TaxReturn::findOrFail($id);
      $organization_id = session("organization_id");
      $tax1701Q = Tax1701Q::where('tax_return_id', $id)->first();

      // If a Tax1701Q exists, redirect to the reportPDF page
      if ($tax1701Q) {
          return redirect()->route('income_return.reportPDF', ['taxReturn' => $taxReturn->id]);
      }
      // Load the organization and its RDO relationship
      $organization = OrgSetup::with("rdo")
          ->where('id', $organization_id)
          ->first();
  
      // Extract the RDO code from the organization data
      $rdoCode = optional($organization->Rdo)->rdo_code ?? '';
  
      // Retrieve the individual and spouse background information
      $individualBackground = $taxReturn->individualBackgroundInformation;
      $spouseBackground = $individualBackground->spouseInformation ?? null;
  
      // Fetch tax option rate for individual background information
      $individualTaxOptionRate = $individualBackground->taxOptionRate()->first();
  
      // Fetch tax option rate for spouse background information if spouse exists
      $spouseTaxOptionRate = $spouseBackground
          ? $spouseBackground->taxOptionRate()->first()
          : null;
  
      // Collect transactions for Individual from individual_transactions table
      $individualTransactionIds = $taxReturn->individualTransaction()->pluck('transaction_id') ?? collect();
  
      // Collect transactions for Spouse from spouse_transactions table, if applicable
      $spouseTransactionIds = $spouseBackground
          ? $taxReturn->spouseTransactions()->pluck('transaction_id') ?? collect()
          : collect(); // Empty collection if no spouse information
  
      // Query and collect all Individual Sales TaxRows
      $individualSalesTaxRows = TaxRow::whereHas('transaction', function ($q) use ($individualTransactionIds) {
          $q->whereIn('id', $individualTransactionIds)
              ->where('transaction_type', 'sales');
      })
      ->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount']) // Eager load related data
      ->get();
  
      // Query and collect all Spouse Sales TaxRows
      $spouseSalesTaxRows = TaxRow::whereHas('transaction', function ($q) use ($spouseTransactionIds) {
          $q->whereIn('id', $spouseTransactionIds)
              ->where('transaction_type', 'sales');
      })
      ->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount']) // Eager load related data
      ->get();
  
      // Combine individual and spouse sales data
      $combinedSalesTaxRows = $individualSalesTaxRows->merge($spouseSalesTaxRows);
  
      // Calculate the total net amount for individual and spouse
      $individualNetAmount = $individualSalesTaxRows->sum('net_amount');
      $spouseNetAmount = $spouseSalesTaxRows->sum('net_amount');
      $totalNetAmount = $individualNetAmount + $spouseNetAmount;
  
      // Initialize cost of sales variables for COGS and Allowable Itemized Deduction
      $individualCOGS = 0;
      $spouseCOGS = 0;
      $individualItemizedDeduction = 0;
      $spouseItemizedDeduction = 0;
  
      // Check if the deduction method for individual is itemized
      if ($individualTaxOptionRate && $individualTaxOptionRate->deduction_method === 'itemized') {
          // Get individual deductible tax rows (Cost of Goods Sold and Itemized Deduction)
          $individualTaxRowsForDeductions = TaxRow::whereHas('coaAccount', function ($q) {
              $q->whereIn('sub_type', ['Cost of Goods Sold', 'Allowable Itemized Deduction']);
          })
          ->whereHas('transaction', function ($q) use ($individualTransactionIds) {
              $q->whereIn('id', $individualTransactionIds);
          })
          ->get();
  
          // Separate COGS and Itemized Deduction for individual
          $individualCOGS = $individualTaxRowsForDeductions->where('coaAccount.sub_type', 'Cost of Goods Sold')->sum('amount');
          $individualItemizedDeduction = $individualTaxRowsForDeductions->where('coaAccount.sub_type', 'Allowable Itemized Deduction')->sum('amount');
      }
  
      // Check if the deduction method for spouse is itemized (if spouse exists)
      if ($spouseTaxOptionRate && $spouseTaxOptionRate->deduction_method === 'itemized') {
          // Get spouse deductible tax rows (Cost of Goods Sold and Itemized Deduction)
          $spouseTaxRowsForDeductions = TaxRow::whereHas('coaAccount', function ($q) {
              $q->whereIn('sub_type', ['Cost of Goods Sold', 'Allowable Itemized Deduction']);
          })
          ->whereHas('transaction', function ($q) use ($spouseTransactionIds) {
              $q->whereIn('id', $spouseTransactionIds);
          })
          ->get();
  
          // Separate COGS and Itemized Deduction for spouse
          $spouseCOGS = $spouseTaxRowsForDeductions->where('coaAccount.sub_type', 'Cost of Goods Sold')->sum('amount');
          $spouseItemizedDeduction = $spouseTaxRowsForDeductions->where('coaAccount.sub_type', 'Allowable Itemized Deduction')->sum('amount');
      }
  
      // Determine the current and previous quarters
      $month = $taxReturn->month;
      $currentQuarter = 'Q' . ceil((int)$month / 3); // Convert $month to an integer before division

      $previousQuarter = $currentQuarter === 'Q1' ? null : 'Q' . (ceil((int)$month / 3) - 1);

  
      // Initialize previous quarter's cumulative income
      $previousCumulativeIncome = 0;
      $spousePreviousCumulativeIncome = 0;
  
    //   // Fetch previous quarter's tax return if applicable
    //   if ($previousQuarter) {
    //       $previousTaxReturn1701Q = Tax1701Q::where('organization_id', $organization_id)
    //           ->where('year', $taxReturn->year) // Same year
    //           ->where('quarter', $previousQuarter) // Previous quarter
    //           ->first();
  
    //       if ($previousTaxReturn1701Q) {
    //           // Extract Item 51 (Cumulative Taxable Income/(Loss))
    //           $previousCumulativeIncome = $previousTaxReturn1701Q->cumulative_taxable_income ?? 0;
    //           $spousePreviousCumulativeIncome = $previousTaxReturn1701Q->spouse_cumulative_taxable_income ?? 0;
    //       }
    //   }
  
      // Pass all required variables to the view
      return view('tax_return.income_report_preview', compact(
          'rdoCode', 
          'taxReturn', 
          'organization', 
          'individualBackground', 
          'spouseBackground', 
          'individualTaxOptionRate', 
          'spouseTaxOptionRate', 
          'combinedSalesTaxRows',
          'individualNetAmount',
          'spouseNetAmount',     
          'totalNetAmount',
          'individualCOGS',  
          'spouseCOGS',
          'individualItemizedDeduction', 
          'spouseItemizedDeduction',
        //   'previousCumulativeIncome', // Pass previous quarter cumulative income
        //   'spousePreviousCumulativeIncome' // Pass spouse's previous quarter cumulative income
      ));
  }
  
  
  public function edit2550Q(TaxReturn $taxReturn)
  {
      $tax2550q = Tax2550Q::where('tax_return_id', $taxReturn->id)->firstOrFail();
      
      return view('tax_return.vat_report_edit', [
          'taxReturn' => $taxReturn,
          'tax2550q' => $tax2550q
      ]);
  }
  




  // Function for showing Percentage Tax Return Table
    public function percentageReturn()
    {
        $organizationId = session('organization_id');
        $taxReturns = TaxReturn::with('user')
            ->where('organization_id', $organizationId)
            ->whereIn('title', ['2551Q', '2551M'])
            ->get();

        return view('tax_return.percentage_return', compact('taxReturns'));
    }
    

        // Function for VAT Tax Return
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
      // Function for Creating Percentage Tax Return
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
    
    

       // Function for Creating 2550Q Value Added Tax Return Report
    public function store2550Q(Request $request, $taxReturn)
    {

        $messages = [
            'period.required' => 'The period field is required.',
        'period.string' => 'The period must be a valid string.',
        'year_ended.required' => 'The year ended field is required.',
        'year_ended.date_format' => 'The year ended must be in the format Y-m.',
        'quarter.required' => 'The quarter field is required.',
        'quarter.string' => 'The quarter must be a valid string.',
        'return_from.required' => 'The return from date is required.',
        'return_from.date' => 'The return from date must be a valid date.',
        'return_to.required' => 'The return to date is required.',
        'return_to.date' => 'The return to date must be a valid date.',
        'amended_return.required' => 'The amended return field is required.',
        'amended_return.in' => 'The amended return field must be yes or no.',
        'short_period_return.required' => 'The short period return field is required.',
        'short_period_return.in' => 'The short period return field must be yes or no.',
        'tin.required' => 'The TIN field is required.',
        'tin.string' => 'The TIN must be a valid string.',
        'tin.max' => 'The TIN should not exceed 20 characters.',
        'rdo_code.required' => 'The RDO code field is required.',
        'rdo_code.string' => 'The RDO code must be a valid string.',
        'rdo_code.max' => 'The RDO code should not exceed 5 characters.',
        'taxpayer_name.required' => 'The taxpayer name is required.',
        'taxpayer_name.string' => 'The taxpayer name must be a valid string.',
        'taxpayer_name.max' => 'The taxpayer name should not exceed 255 characters.',
        'registered_address.required' => 'The registered address is required.',
        'registered_address.string' => 'The registered address must be a valid string.',
        'registered_address.max' => 'The registered address should not exceed 255 characters.',
        'zip_code.required' => 'The zip code is required.',
        'zip_code.string' => 'The zip code must be a valid string.',
        'zip_code.max' => 'The zip code should not exceed 10 characters.',
        'contact_number.max' => 'The contact number should not exceed 15 characters.',
        'email_address.email' => 'The email address must be a valid email.',
        'email_address.max' => 'The email address should not exceed 255 characters.',
        'taxpayer_classification.required' => 'The taxpayer classification is required.',
        'taxpayer_classification.string' => 'The taxpayer classification must be a valid string.',
        'taxpayer_classification.max' => 'The taxpayer classification should not exceed 50 characters.',
        'tax_relief.required' => 'The tax relief field is required.',
        'tax_relief.in' => 'The tax relief field must be yes or no.',
        'yes_specify.max' => 'The specify field should not exceed 255 characters.',
        'creditable_vat_withheld.numeric' => 'The creditable VAT withheld must be a number.',
        'advance_vat_payment.numeric' => 'The advance VAT payment must be a number.',
        'vat_paid_if_amended.numeric' => 'The VAT paid if amended must be a number.',
        'other_credits_specify.max' => 'The other credits specify field should not exceed 255 characters.',
        'other_credits_specify_amount.numeric' => 'The other credits specify amount must be a number.',
        'total_tax_credits.numeric' => 'The total tax credits must be a number.',
        'tax_still_payable.numeric' => 'The tax still payable must be a number.',
        'surcharge.numeric' => 'The surcharge must be a number.',
        'interest.numeric' => 'The interest must be a number.',
        'compromise.numeric' => 'The compromise must be a number.',
        'total_penalties.numeric' => 'The total penalties must be a number.',
        'total_amount_payable.numeric' => 'The total amount payable must be a number.',
        'vatable_sales.numeric' => 'The vatable sales must be a number.',
        'vatable_sales_tax_amount.numeric' => 'The vatable sales tax amount must be a number.',
        'zero_rated_sales.numeric' => 'The zero rated sales must be a number.',
        'exempt_sales.numeric' => 'The exempt sales must be a number.',
        'total_sales.numeric' => 'The total sales must be a number.',
        'total_output_tax.numeric' => 'The total output tax must be a number.',
        'uncollected_receivable_vat.numeric' => 'The uncollected receivable VAT must be a number.',
        'recovered_uncollected_receivables.numeric' => 'The recovered uncollected receivables must be a number.',
        'total_adjusted_output_tax.numeric' => 'The total adjusted output tax must be a number.',
        'input_carried_over.numeric' => 'The input carried over must be a number.',
        'input_tax_deferred.numeric' => 'The input tax deferred must be a number.',
        'transitional_input_tax.numeric' => 'The transitional input tax must be a number.',
        'presumptive_input_tax.numeric' => 'The presumptive input tax must be a number.',
        'other_specify.max' => 'The other specify field should not exceed 255 characters.',
        'other_input_tax.numeric' => 'The other input tax must be a number.',
        'total_input_tax.numeric' => 'The total input tax must be a number.',
        'domestic_purchase.numeric' => 'The domestic purchase must be a number.',
        'domestic_purchase_input_tax.numeric' => 'The domestic purchase input tax must be a number.',
        'services_non_resident.numeric' => 'The services non-resident must be a number.',
        'services_non_resident_input_tax.numeric' => 'The services non-resident input tax must be a number.',
        'importations.numeric' => 'The importations must be a number.',
        'importations_input_tax.numeric' => 'The importations input tax must be a number.',
        'purchases_others_specify.max' => 'The other specify field should not exceed 255 characters.',
        'purchases_others_specify_amount.numeric' => 'The other specify amount must be a number.',
        'purchases_others_specify_input_tax.numeric' => 'The other specify input tax must be a number.',
        'domestic_no_input.numeric' => 'The domestic no input must be a number.',
        'tax_exempt_importation.numeric' => 'The tax exempt importation must be a number.',
        'total_current_purchase.numeric' => 'The total current purchase must be a number.',
        'total_current_purchase_input_tax.numeric' => 'The total current purchase input tax must be a number.',
        'total_available_input_tax.numeric' => 'The total available input tax must be a number.',
        'importation_million_deferred_input_tax.numeric' => 'The importation million deferred input tax must be a number.',
        'attributable_vat_exempt_input_tax.numeric' => 'The attributable VAT exempt input tax must be a number.',
        'vat_refund_input_tax.numeric' => 'The VAT refund input tax must be a number.',
        'unpaid_payables_input_tax.numeric' => 'The unpaid payables input tax must be a number.',
        'other_deduction_specify.max' => 'The other deduction specify field should not exceed 255 characters.',
        'other_deduction_specify_input_tax.numeric' => 'The other deduction specify input tax must be a number.',
        'total_deductions_input_tax.numeric' => 'The total deductions input tax must be a number.',
        'settled_unpaid_input_tax.numeric' => 'The settled unpaid input tax must be a number.',
        'adjusted_deductions_input_tax.numeric' => 'The adjusted deductions input tax must be a number.',
        'total_allowable_input_Tax.numeric' => 'The total allowable input tax must be a number.',
        'excess_input_tax.numeric' => 'The excess input tax must be a number.',
    ];
     // Step 1: Validate incoming data
        $validatedData = $request->validate([
            'period' => 'required|string',
            'year_ended' => 'required|date_format:Y-m',
            'quarter' => 'required|string',
            'return_from' => 'required|date',
            'return_to' => 'required|date',
            'amended_return' => 'required|string|in:yes,no',
            'short_period_return' => 'required|string|in:yes,no',
            'tin' => 'required|string|max:20',
            'rdo_code' => 'required|string|max:5',
            'taxpayer_name' => 'required|string|max:255',
            'registered_address' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'contact_number' => 'nullable|string|max:15',
            'email_address' => 'nullable|email|max:255',
            'taxpayer_classification' => 'required|string|max:50',
            'tax_relief' => 'required|string|in:yes,no',
            'yes_specify' => 'nullable|string|max:255',
            'creditable_vat_withheld' => 'nullable|numeric',
            'advance_vat_payment' => 'nullable|numeric',
            'vat_paid_if_amended' => 'nullable|numeric',
            'other_credits_specify' => 'nullable|string|max:255',
            'other_credits_specify_amount' => 'nullable|numeric',
            'total_tax_credits' => 'nullable|numeric',
            'tax_still_payable' => 'nullable|numeric',
            'surcharge' => 'nullable|numeric',
            'interest' => 'nullable|numeric',
            'compromise' => 'nullable|numeric',
            'total_penalties' => 'nullable|numeric',
            'total_amount_payable' => 'nullable|numeric',
            'vatable_sales' => 'nullable|numeric',
            'vatable_sales_tax_amount' => 'nullable|numeric',
            'zero_rated_sales' => 'nullable|numeric',
            'exempt_sales' => 'nullable|numeric',
            'total_sales' => 'nullable|numeric',
            'total_output_tax' => 'nullable|numeric',
            'uncollected_receivable_vat' => 'nullable|numeric',
            'recovered_uncollected_receivables' => 'nullable|numeric',
            'total_adjusted_output_tax' => 'nullable|numeric',
            'input_carried_over' => 'nullable|numeric',
            'input_tax_deferred' => 'nullable|numeric',
            'transitional_input_tax' => 'nullable|numeric',
            'presumptive_input_tax' => 'nullable|numeric',
            'other_specify' => 'nullable|string|max:255',
            'other_input_tax' => 'nullable|numeric',
            'total_input_tax' => 'nullable|numeric',
            'domestic_purchase' => 'nullable|numeric',
            'domestic_purchase_input_tax' => 'nullable|numeric',
            'services_non_resident' => 'nullable|numeric',
            'services_non_resident_input_tax' => 'nullable|numeric',
            'importations' => 'nullable|numeric',
            'importations_input_tax' => 'nullable|numeric',
            'purchases_others_specify' => 'nullable|string|max:255',
            'purchases_others_specify_amount' => 'nullable|numeric',
            'purchases_others_specify_input_tax' => 'nullable|numeric',
            'domestic_no_input' => 'nullable|numeric',
            'tax_exempt_importation' => 'nullable|numeric',
            'total_current_purchase' => 'nullable|numeric',
            'total_current_purchase_input_tax' => 'nullable|numeric',
            'total_available_input_tax' => 'nullable|numeric',
            'importation_million_deferred_input_tax' => 'nullable|numeric',
            'attributable_vat_exempt_input_tax' => 'nullable|numeric',
            'vat_refund_input_tax' => 'nullable|numeric',
            'unpaid_payables_input_tax' => 'nullable|numeric',
            'other_deduction_specify' => 'nullable|string|max:255',
            'other_deduction_specify_input_tax' => 'nullable|numeric',
            'total_deductions_input_tax' => 'nullable|numeric',
            'settled_unpaid_input_tax' => 'nullable|numeric',
            'adjusted_deductions_input_tax' => 'nullable|numeric',
            'total_allowable_input_Tax' => 'nullable|numeric',
            'excess_input_tax' => 'nullable|numeric',
        ], $messages);
        // Step 2: Attach the tax return ID to the validated data
        $validatedData['tax_return_id'] = $taxReturn;
  
    
        // Step 3: Create or update the Tax2550Q record
        $tax2550Q = Tax2550Q::updateOrCreate(
            ['tax_return_id' => $taxReturn], // Find existing record by tax_return_id
            $validatedData // Use validated data for creation or update
        );
    
        // Step 4: Return a response
        return redirect()
            ->route('tax_return.2550q.pdf', ['taxReturn' => $taxReturn])
            ->with('success', 'Tax return successfully submitted and PDF generated.');
            
        
    }
    
      // Function for Creating 2551Q Percentage Tax Return Report
      public function store2551Q(Request $request, $taxReturn)
      {
          try {
              // Custom validation messages
              $messages = [
                  'period.required' => 'The period field is required.',
                  'year_ended.required' => 'The year ended field is required.',
                  'year_ended.date' => 'The year ended must be a valid date.',
                  'quarter.required' => 'The quarter field is required.',
                  'amended_return.required' => 'Please specify if this is an amended return.',
                  'sheets_attached.required' => 'The number of sheets attached is required.',
                  'sheets_attached.integer' => 'The sheets attached must be a number.',
                  'tin.required' => 'TIN is required.',
                  'rdo_code.required' => 'RDO code is required.',
                  'taxpayer_name.required' => 'Taxpayer name is required.',
                  'registered_address.required' => 'Registered address is required.',
                  'zip_code.required' => 'ZIP code is required.',
                  'contact_number.required' => 'Contact number is required.',
                  'email_address.required' => 'Email address is required.',
                  'email_address.email' => 'Please enter a valid email address.',
                  'tax_relief.required' => 'Tax relief information is required.',
                  'availed_tax_rate.required' => 'Availed tax rate is required.',
                  'tax_due.required' => 'Tax due amount is required.',
                  'tax_due.numeric' => 'Tax due must be a number.',
                  'creditable_tax.required' => 'Creditable tax amount is required.',
                  'creditable_tax.numeric' => 'Creditable tax must be a number.',
                  'amended_tax.numeric' => 'Amended tax must be a number.',
                  'other_tax_amount.numeric' => 'Other tax amount must be a number.',
                  'total_tax_credits.required' => 'Total tax credits is required.',
                  'total_tax_credits.numeric' => 'Total tax credits must be a number.',
                  'tax_still_payable.required' => 'Tax still payable amount is required.',
                  'tax_still_payable.numeric' => 'Tax still payable must be a number.',
                  'surcharge.numeric' => 'Surcharge must be a number.',
                  'interest.numeric' => 'Interest must be a number.',
                  'compromise.numeric' => 'Compromise must be a number.',
                  'total_penalties.numeric' => 'Total penalties must be a number.',
                  'total_amount_payable.required' => 'Total amount payable is required.',
                  'total_amount_payable.numeric' => 'Total amount payable must be a number.',
                  'schedule.array' => 'Schedule must be an array.'
              ];
      
              // Validate with custom messages
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
                  'schedule' => 'nullable|array',
              ], $messages);
      
              // Add the tax_return_id to the validated data
              $validatedData['tax_return_id'] = $taxReturn;
      
              // Database transaction to ensure data integrity
              DB::beginTransaction();
      
              try {
                  // Create or update Tax2551Q
                  $tax2551Q = Tax2551Q::updateOrCreate(
                      ['tax_return_id' => $taxReturn],
                      $validatedData
                  );
      
                  // Process schedule if exists
                  if (isset($validatedData['schedule']) && !empty($validatedData['schedule'])) {
                      foreach ($validatedData['schedule'] as $scheduleData) {
                          // Validate schedule data
                          if (!isset($scheduleData['taxable_amount']) || !isset($scheduleData['atc_code'])) {
                              throw new \Exception('Invalid schedule data provided');
                          }
      
                          // Clean and validate tax_base for each schedule item
                          $scheduleData['taxable_amount'] = preg_replace('/[^0-9.]/', '', $scheduleData['taxable_amount']);
                          $scheduleData['taxable_amount'] = (float) $scheduleData['taxable_amount'];
      
                          Tax2551QSchedule::updateOrCreate(
                              [
                                  '2551q_id' => $tax2551Q->id,
                                  'atc_code' => $scheduleData['atc_code'],
                              ],
                              [
                                  'tax_base' => $scheduleData['taxable_amount'],
                                  'tax_rate' => $scheduleData['tax_rate'],
                                  'tax_due' => $scheduleData['tax_due'],
                              ]
                          );
                      }
                  }
      
                  DB::commit();
      
                  return redirect()
                      ->route('tax_return.2551q.pdf', ['taxReturn' => $taxReturn])
                      ->with('success', 'Tax return successfully submitted and PDF generated.');
      
              } catch (\Exception $e) {
                  DB::rollBack();
                  return redirect()
                      ->back()
                      ->withInput()
                      ->with('error', 'Error processing schedule data: ' . $e->getMessage());
              }
      
          } catch (\Illuminate\Validation\ValidationException $e) {
              return redirect()
                  ->back()
                  ->withErrors($e->validator)
                  ->withInput();
          } catch (\Exception $e) {
              return redirect()
                  ->back()
                  ->withInput()
                  ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
          }
      }
     // Function for showing SLSP Data of Value Added Tax Return
     public function showSlspData(TaxReturn $taxReturn, Request $request)
     {
         // Retrieve the selected type from the request
         $type = $request->get('type', 'sales'); // Default to 'sales' if no type is provided
     
         // Retrieve the 'perPage' value from the request, defaulting to 5 if not provided
         $perPage = $request->get('perPage', 5);
     
         // Retrieve the search query from the request
         $search = $request->get('search', '');
     
         // Get the transaction IDs linked to this tax return
         $transactionIds = $taxReturn->transactions->pluck('id');
     
         // Define the query
         $paginatedTaxRows = TaxRow::where(function ($query) use ($type, $search) {
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
     
             // Apply search filter if search term is provided
             if ($search) {
                 $query->where(function ($q) use ($search) {
                     $q->whereHas('transaction.contactDetails', function ($q) use ($search) {
                         $q->where('bus_name', 'like', '%' . $search . '%')
                           ->orWhere('contact_address', 'like', '%' . $search . '%')
                           ->orWhere('contact_tin', 'like', '%' . $search . '%');
                     })
                     ->orWhere('description', 'like', '%' . $search . '%')
                     ->orWhereHas('transaction', function ($q) use ($search) {
                         $q->where('inv_number', 'like', '%' . $search . '%');
                     })
                     ->orWhereHas('atc', function ($q) use ($search) {
                         $q->where('tax_code', 'like', '%' . $search . '%');
                     });
                 });
             }
         })
         ->whereHas('transaction', function ($query) use ($transactionIds) {
             $query->whereIn('id', $transactionIds); // Ensure transactions belong to the current tax return
         })
         ->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount']) // Eager load relationships
         ->paginate($perPage); // Use perPage to control pagination size
     
         return view('tax_return.vat_show', compact('taxReturn', 'paginatedTaxRows', 'type', 'search'));
     }
     
     // Function to show Income Sales Data
     public function showIncomeSalesData(TaxReturn $taxReturn, Request $request)
     {
         // Get Individual and Spouse Background Information
         $individualBackground = $taxReturn->individualBackgroundInformation;
         $spouseBackground = $individualBackground->spouseInformation ?? null;
         $type = $request->input('type', 'default');
     
         // Get search term and active tab
         $search = $request->input('search', ''); // Default to empty string if no search term
         $activeTab = $request->get('tab', 'individual'); // Default to 'individual' if no tab is specified
     
         // Collect transactions for Individual from individual_transactions table
         $individualTransactionIds = $taxReturn->individualTransaction()->pluck('transaction_id') ?? collect();
     
         // Collect transactions for Spouse from spouse_transactions table, if applicable
         $spouseTransactionIds = $spouseBackground 
             ? $taxReturn->spouseTransactions()->pluck('transaction_id') ?? collect() 
             : collect(); // Empty collection if no spouse information
     
         // Query for Individual Sales TaxRows
         $individualSalesTaxRowsQuery = TaxRow::whereHas('transaction', function ($q) use ($individualTransactionIds, $search) {
             $q->whereIn('id', $individualTransactionIds)
               ->where('transaction_type', 'sales');
     
             // Apply search filter if search term is provided
             if (!empty($search)) {
                 $q->where(function ($query) use ($search) {
                     $query->where('inv_number', 'like', "%$search%")
                           ->orWhere('description', 'like', "%$search%")
                           ->orWhere('reference', 'like', "%$search%")
                           ->orWhereHas('contactDetails', function ($contactQuery) use ($search) {
                               $contactQuery->where('bus_name', 'like', "%$search%")
                                            ->orWhere('contact_address', 'like', "%$search%")
                                            ->orWhere('contact_tin', 'like', "%$search%");
                           });
                 });
             }
         });
     
         // Query for Spouse Sales TaxRows
         $spouseSalesTaxRowsQuery = TaxRow::whereHas('transaction', function ($q) use ($spouseTransactionIds, $search) {
             $q->whereIn('id', $spouseTransactionIds)
               ->where('transaction_type', 'sales');
     
             // Apply search filter if search term is provided
             if (!empty($search)) {
                 $q->where(function ($query) use ($search) {
                     $query->where('inv_number', 'like', "%$search%")
                           ->orWhere('description', 'like', "%$search%")
                           ->orWhere('reference', 'like', "%$search%")
                           ->orWhereHas('contactDetails', function ($contactQuery) use ($search) {
                               $contactQuery->where('bus_name', 'like', "%$search%")
                                            ->orWhere('contact_address', 'like', "%$search%")
                                            ->orWhere('contact_tin', 'like', "%$search%");
                           });
                 });
             }
         });
     
         // Paginate Individual and Spouse Sales TaxRows
         $individualSalesTaxRows = $individualSalesTaxRowsQuery
             ->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount']) // Eager load related data
             ->paginate(5);
     
         $spouseSalesTaxRows = $spouseSalesTaxRowsQuery
             ->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount']) // Eager load related data
             ->paginate(5);
     
         // Return to view with paginated data and activeTab
         return view('tax_return.income_show_sales', [
             'taxReturn' => $taxReturn,
             'individualTaxRows' => $individualSalesTaxRows,
             'spouseTaxRows' => $spouseSalesTaxRows,
             'type' => $type,
             'activeTab' => $activeTab, // Pass the activeTab to the view
             'search' => $search, // Include search term for the view
         ]);
     }
     

   // Function to show Income Sales Data
public function showIncomeCoaData(TaxReturn $taxReturn, Request $request)
{
    // Get Individual and Spouse Background Information
    $individualBackground = $taxReturn->individualBackgroundInformation;
    $spouseBackground = $individualBackground->spouseInformation ?? null;
    $type = $request->input('type', 'default');
    $search = $request->input('search'); // Get the search term from the request

    // Collect transactions for Individual from individual_transactions table
    $individualTransactionIds = $taxReturn->individualTransaction()->pluck('transaction_id') ?? collect();

    // Collect transactions for Spouse from spouse_transactions table, if applicable   
    $spouseTransactionIds = $spouseBackground
                               ? $taxReturn->spouseTransactions()->pluck('transaction_id') ?? collect() 
                               : collect(); // Empty collection if no spouse information

    // Query for Individual COA TaxRows (Cost of Goods Sold and Allowable Itemized Deduction)
    $individualTaxRowsQuery = TaxRow::whereHas('coaAccount', function ($q) {
        $q->whereIn('sub_type', ['Cost of Goods Sold', 'Allowable Itemized Deduction']);
    });

    // Add condition for Individual transactions if applicable (only filter if IDs are provided)
    $individualTaxRowsQuery->whereHas('transaction', function ($q) use ($individualTransactionIds) {
        $q->whereIn('id', $individualTransactionIds);
    });

    // If search term is provided, filter based on description or other relevant fields
    if ($search) {
        $individualTaxRowsQuery->where(function ($q) use ($search) {
            $q->where('description', 'like', "%$search%")
              ->orWhereHas('transaction.contactDetails', function ($q) use ($search) {
                  $q->where('bus_name', 'like', "%$search%")
                    ->orWhere('contact_address', 'like', "%$search%")
                    ->orWhere('contact_tin', 'like', "%$search%");
              });
        });
    }

    // Query for Spouse COA TaxRows (Cost of Goods Sold and Allowable Itemized Deduction)
    $spouseTaxRowsQuery = TaxRow::whereHas('coaAccount', function ($q) {
        $q->whereIn('sub_type', ['Cost of Goods Sold', 'Allowable Itemized Deduction']);
    });

    // Add condition for Spouse transactions if applicable (only filter if IDs are provided)
    $spouseTaxRowsQuery->whereHas('transaction', function ($q) use ($spouseTransactionIds) {
        $q->whereIn('id', $spouseTransactionIds);
    });

    // If search term is provided, filter based on description or other relevant fields for spouse
    if ($search) {
        $spouseTaxRowsQuery->where(function ($q) use ($search) {
            $q->where('description', 'like', "%$search%")
              ->orWhereHas('transaction.contactDetails', function ($q) use ($search) {
                  $q->where('bus_name', 'like', "%$search%")
                    ->orWhere('contact_address', 'like', "%$search%")
                    ->orWhere('contact_tin', 'like', "%$search%");
              });
        });
    }

    // Paginate Individual and Spouse TaxRows
    $individualTaxRows = $individualTaxRowsQuery
        ->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount']) // Eager load related data
        ->paginate(5); // Paginate the results

    $spouseTaxRows = $spouseTaxRowsQuery
        ->with(['transaction.contactDetails', 'taxType', 'atc', 'coaAccount']) // Eager load related data
        ->paginate(5); // Paginate results for spouse transactions

    // Get the current activeTab from the request, or default to 'individual'
    $activeTab = $request->get('tab', 'individual');

    // Return to view with paginated data and activeTab
    return view('tax_return.income_show_coa', [
        'taxReturn' => $taxReturn,
        'individualTaxRows' => $individualTaxRows,
        'spouseTaxRows' => $spouseTaxRows,
        'type' => $type,
        'activeTab' => $activeTab, // Pass the activeTab to the view
    ]);
}





     
     
     
        // Function to show Summary Report on Value-Added Tax Return 2550Q
     
     
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
        
            // Apply search filter if provided
            $searchTerm = $request->input('search');
            if ($searchTerm) {
                $summaryCollection = $summaryCollection->filter(function ($item) use ($searchTerm) {
                    return stripos($item['tax_type'], $searchTerm) !== false ||
                           stripos((string)$item['vatable_sales'], $searchTerm) !== false ||
                           stripos((string)$item['tax_due'], $searchTerm) !== false ||
                           stripos((string)$item['tax_rate'], $searchTerm) !== false;
                });
            }
        
            // Determine per page
            $perPage = $request->input('perPage', 5); // Default to 5 if not specified
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
        
            // Add query parameters to pagination links
            $paginatedSummaryData->appends($request->only(['search', 'perPage']));
        
            // Return the view with the paginated summary data
            return view('tax_return.vat_summary', compact('taxReturn', 'paginatedSummaryData'));
        }
         
        // Function to show Percentage Tax SLSP Data 2551Q
    
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
    
            // Function to show Percentage Tax Summary Page 2551Q
    
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


    

            // Function to show Value Added Tax Return Report 

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
                // Function to show Percentage Tax Report (PDF)
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
    public function percentageEdit($id)
    {
        // Get the organization ID from the session
        $organizationId = session('organization_id');
    
        if (!$organizationId) {
            return redirect()->route('organizations.index')->with('error', 'Please select an organization.');
        }
    
        // Retrieve the tax return
        $taxReturn = TaxReturn::where('id', $id)
                              ->where('organization_id', $organizationId)
                              ->firstOrFail();
    
        // Get the related form data
        $formData = Tax2551Q::where('tax_return_id', $taxReturn->id)->first();
    
        // Get the organization data (assuming you have a relationship set up)
        $organization = OrgSetup::find($organizationId);
    
        // Get the transaction IDs related to this tax return
        $transactionIds = $taxReturn->transactions->pluck('id');
    
        // Retrieve the TaxRows based on transactions
        $taxRows = TaxRow::whereHas('transaction', function ($query) use ($transactionIds) {
            $query->where('tax_type', 2)
                  ->where('transaction_type', 'sales')
                  ->where('status', 'draft')
                  ->whereIn('id', $transactionIds);
        })->with(['transaction.contactDetails', 'atc', 'taxType'])
          ->get();
    
        // Group the TaxRows by ATC and calculate the summary
        $groupedData = $taxRows->groupBy(function ($taxRow) {
            return $taxRow->atc->tax_code;
        });
    
        // Calculate summary data for each group
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
    
        // Pass the form data, tax return, organization, and summary data to the view for editing
        return view('tax_return.percentage_report_edit', [
            'taxReturn' => $taxReturn,
            'formData' => $formData,
            'organization' => $organization,
            'summaryData' => $summaryData, // Add the summary data to the view
        ]);
    }
    
    
    public function showVatReportPDF(TaxReturn $taxReturn)
    {
        // Get data from the `2550q` table
        $formData = Tax2550Q::where('tax_return_id', $taxReturn->id)->first();
        if (!$formData) {
            return dd('No data found in the 2550q table for this tax return.');
        }
 
        // Initialize fields array
        $fields = [
            '1A' => $formData->year_ended === '2021-12' ? 'X' : '',
            '1B' => $formData->year_ended === '2021-12' ? '' : 'X',
           '1C' => implode('/', array_reverse(explode('-', $formData->year_ended))),
            '3A' => $formData->quarter === '1st' ? 'X' : '',
            '3B' => $formData->quarter === '2nd' ? 'X' : '',
            '3C' => $formData->quarter === '3rd' ? 'X' : '',
            '3D' => $formData->quarter === '4th' ? 'X' : '',
            '4A' =>$formData->return_from ?? '',
            '4B' =>$formData->return_to ?? '',
            '5Y' => $formData->amended_return === 'yes' ? 'X' : '',
            '5N' => $formData->amended_return === 'no' ? 'X' : '',
            '6Y' => $formData->short_period_return === 'yes' ? 'X' : '',
            '6N' => $formData->short_period_return === 'no' ? 'X' : '',
            '7A' => explode('-', $formData->tin)[0] ?? '',
            '7B' => explode('-', $formData->tin)[1] ?? '',
            '7C' => explode('-', $formData->tin)[2] ?? '',
            '7D' => explode('-', $formData->tin)[3] ?? '',
            '8' => $formData->rdo_code ?? '',
            '9' => $formData->taxpayer_name ?? '',
            '10' => $formData->registered_address ?? '',
            '10A' => $formData->zip_code ?? '',
            '11' => $formData->contact_number ?? '',
            '12' => $formData->email_address ?? '',
            '13A' => $formData->taxpayer_classification === 'Micro' ? 'X' : '',
            '13B' => $formData->taxpayer_classification === 'Small' ? 'X' : '',
            '13C' => $formData->taxpayer_classification === 'Medium' ? 'X' : '',
            '13D' => $formData->taxpayer_classification === 'Large' ? 'X' : '',
            '14Y' => $formData->tax_relief === 'yes' ? 'X' : '',
            '14N' => $formData->tax_relief === 'no' ? 'X' : '',
            '14A' => $formData->yes_specify ?? '',
            '15' => floor($formData->excess_input_tax ?? 0),
            '15D' => ($formData->excess_input_tax ?? 0) * 100 % 100,
            '16' => floor($formData->creditable_vat_withheld ?? 0),
            '16D' => ($formData->creditable_vat_withheld ?? 0) * 100 % 100,
            '17' => floor($formData->advance_vat_payment ?? 0),
            '17D' => ($formData->advance_vat_payment ?? 0) * 100 % 100,
            '18' => floor($formData->vat_paid_if_amended ?? 0),
            '18D' => ($formData->vat_paid_if_amended ?? 0) * 100 % 100,
            '19S' => $formData->other_credits_specify ?? '',
            '19' => floor($formData->other_credits_specify_amount ?? 0),
            '19D' => ($formData->other_credits_specify_amount ?? 0) * 100 % 100,
            '20' => floor($formData->total_tax_credits ?? 0),
            '20D' => ($formData->total_tax_credits ?? 0) * 100 % 100,
            '21' => floor($formData->tax_still_payable ?? 0),
            '21D' => ($formData->tax_still_payable ?? 0) * 100 % 100,
            '22' => floor($formData->surcharge ?? 0),
            '22D' => ($formData->surcharge ?? 0) * 100 % 100,
            '23' => floor($formData->interest ?? 0),
            '23D' => ($formData->interest ?? 0) * 100 % 100,
            '24' => floor($formData->compromise ?? 0),
            '24D' => ($formData->compromise ?? 0) * 100 % 100,
            '25' => floor($formData->total_penalties ?? 0),
            '25D' => ($formData->total_penalties ?? 0) * 100 % 100,
            '26' => floor($formData->total_amount_payable ?? 0),
            '26D' => ($formData->total_amount_payable ?? 0) * 100 % 100,
            'TINRow1' => $formData->tin ?? '',
            '31A' => floor($formData->vatable_sales ?? 0),
            '31AD' => ($formData->vatable_sales ?? 0) * 100 % 100,
            '31B' => floor($formData->vatable_sales_tax_amount ?? 0),
            '31BD' => ($formData->vatable_sales_tax_amount ?? 0) * 100 % 100,
            '32A' => floor($formData->zero_rated_sales ?? 0),
            '32AD' => ($formData->zero_rated_sales ?? 0) * 100 % 100,
            '33A' => floor($formData->exempt_sales ?? 0),
            '33AD' => ($formData->exempt_sales ?? 0) * 100 % 100,
            '34A' => floor($formData->total_sales ?? 0),
            '34AD' => ($formData->total_sales ?? 0) * 100 % 100,
            '34B' => floor($formData->total_output_tax ?? 0),
            '34BD' => ($formData->total_output_tax ?? 0) * 100 % 100,
            '35B' => floor($formData->uncollected_receivable_vat ?? 0),
            '35BD' => ($formData->uncollected_receivable_vat ?? 0) * 100 % 100,
            '36B' => floor($formData->recovered_uncollected_receivables ?? 0),
            '36BD' => ($formData->recovered_uncollected_receivables ?? 0) * 100 % 100,
            '37B' => floor($formData->total_adjusted_output_tax ?? 0),
            '37BD' => ($formData->total_adjusted_output_tax ?? 0) * 100 % 100,
            '38B' => floor($formData->input_carried_over ?? 0),
            '38BD' => ($formData->input_carried_over ?? 0) * 100 % 100,
            '39B' => floor($formData->input_tax_deferred ?? 0),
            '39BD' => ($formData->input_tax_deferred ?? 0) * 100 % 100,
            '40B' => floor($formData->transitional_input_tax ?? 0),
            '40BD' => ($formData->transitional_input_tax ?? 0) * 100 % 100,
            '41B' => floor($formData->presumptive_input_tax ?? 0),
            '41BD' => ($formData->presumptive_input_tax ?? 0) * 100 % 100,
            '42S' => $formData->other_specify ?? '',
            '42B' => floor($formData->other_input_tax ?? 0),
            '42BD' => ($formData->other_input_tax ?? 0) * 100 % 100,
            '43B' => floor($formData->total_input_tax ?? 0),
            '43BD' => ($formData->total_input_tax ?? 0) * 100 % 100,
            '44A' => floor($formData->domestic_purchase ?? 0),
            '44AD' => ($formData->domestic_purchase ?? 0) * 100 % 100,
            '44B' => floor($formData->domestic_purchase_input_tax ?? 0),
            '44BD' => ($formData->domestic_purchase_input_tax ?? 0) * 100 % 100,
            '45A' => floor($formData->services_non_resident ?? 0),
            '45AD' => ($formData->services_non_resident ?? 0) * 100 % 100,
            '45B' => floor($formData->services_non_resident_input_tax ?? 0),
            '45BD' => ($formData->services_non_resident_input_tax ?? 0) * 100 % 100,
            '46A' => floor($formData->importations ?? 0),
            '46AD' => ($formData->importations ?? 0) * 100 % 100,
            '46B' => floor($formData->importations_input_tax ?? 0),
            '46BD' => ($formData->importations_input_tax ?? 0) * 100 % 100,
            '47S' => $formData->purchases_others_specify ?? '',
            '47A' => floor($formData->purchases_others_specify_amount ?? 0),
            '47AD' => ($formData->purchases_others_specify_amount ?? 0) * 100 % 100,
            '47B' => floor($formData->purchases_others_specify_input_tax ?? 0),
            '47BD' => ($formData->purchases_others_specify_input_tax ?? 0) * 100 % 100,
            '48A' => floor($formData->domestic_no_input ?? 0),
            '48AD' => ($formData->domestic_no_input ?? 0) * 100 % 100,
            '49A' => floor($formData->tax_exempt_importation ?? 0),
            '49AD' => ($formData->tax_exempt_importation ?? 0) * 100 % 100,
            '50A' => floor($formData->total_current_purchase ?? 0),
            '50AD' => ($formData->total_current_purchase ?? 0) * 100 % 100,
            '50B' => floor($formData->total_current_purchase_input_tax ?? 0),
            'fill_67' => ($formData->total_current_purchase_input_tax ?? 0) * 100 % 100,
            '51B' => floor($formData->total_available_input_tax ?? 0),
            '51BD' => ($formData->total_available_input_tax ?? 0) * 100 % 100,
            '52B' => floor($formData->importation_million_deferred_input_tax	 ?? 0),
            '52BD' => ($formData->importation_million_deferred_input_tax	 ?? 0) * 100 % 100,
            '53B' => floor($formData->attributable_vat_exempt_input_tax ?? 0),
            '53BD' => ($formData->attributable_vat_exempt_input_tax ?? 0) * 100 % 100,
            '54B' => floor($formData->vat_refund_input_tax ?? 0),
            '54BD' => ($formData->vat_refund_input_tax ?? 0) * 100 % 100,
            '55B' => floor($formData->unpaid_payables_input_tax ?? 0),
            '55BD' => ($formData->unpaid_payables_input_tax ?? 0) * 100 % 100,
            '56S' => $formData->other_deduction_specify ?? '',
            '56B' => floor($formData->other_deduction_specify_input_tax ?? 0),
            '56BD' => ($formData->other_deduction_specify_input_tax ?? 0) * 100 % 100,
            '57B' => floor($formData->total_deductions_input_tax ?? 0),
            '57BD' => ($formData->total_deductions_input_tax ?? 0) * 100 % 100,
            '58B' => floor($formData->settled_unpaid_input_tax ?? 0),
            '58BD' => ($formData->settled_unpaid_input_tax ?? 0) * 100 % 100,
            '59B' => floor($formData->adjusted_deductions_input_tax ?? 0),
            '59BD' => ($formData->adjusted_deductions_input_tax ?? 0) * 100 % 100,
            '60B' => floor($formData->total_allowable_input_Tax ?? 0),
            '60BD' => ($formData->total_allowable_input_Tax ?? 0) * 100 % 100,
            '61B' => floor($formData->excess_input_tax ?? 0),
            '61BD' => ($formData->excess_input_tax ?? 0) * 100 % 100,
            
            
        ];
    

    
        // Define the PDF template path
        $pdfTemplatePath = public_path('pdfs/2550Q_2024_Updated.pdf');
    
        // Check if the PDF template exists
        if (!file_exists($pdfTemplatePath)) {
            return dd('PDF template not found at: ' . $pdfTemplatePath);
        }
    
        // Create a new PDF instance
        $pdf = new \mikehaertl\pdftk\Pdf($pdfTemplatePath);
    
        // Fill the PDF with the data
        $result = $pdf->fillForm($fields)
            ->needAppearances()
            ->saveAs(storage_path('app/public/2550Q.pdf'));
    
        // Check for errors
        if (!$result) {
            Log::error('PDF fill form error: ' . $pdf->getError());
            return dd('PDF fill form failed: ' . $pdf->getError());
        }
    
        // Check if the output PDF was created successfully
        $outputPdfPath = storage_path('app/public/2550Q.pdf');
        if (!file_exists($outputPdfPath)) {
            return dd('PDF was not created at: ' . $outputPdfPath);
        }
    
        // Serve the filled PDF in a view
        return view('tax_return.vat_report', [
            'taxReturn' => $taxReturn,
            'pdfPath' => asset('storage/2550Q.pdf'), // Serve the PDF from public storage
        ]);
    }
    
    


    




    //Soft delete
    public function destroy(Request $request)
    {
        // Get the array of IDs from the request
        $ids = $request->input('ids');  // Assuming the IDs are passed as an array
        
        // Check if there are IDs to delete
        if (empty($ids)) {
            return back()->with('error', 'No rows selected for deletion.');
        }
        
        // Perform the soft delete on the selected IDs
        TaxReturn::whereIn('id', $ids)->delete();  // Soft delete the tax returns by their IDs
        
        return back()->with('success', 'Selected tax returns deleted successfully!');
    }
    
}

