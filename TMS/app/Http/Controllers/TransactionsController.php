<?php

namespace App\Http\Controllers;

use App\Models\IndividualTransaction;
use App\Models\OrgSetup;
use App\Models\SpouseTransaction;
use App\Models\Transactions;
use App\Http\Requests\StoreTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Imports\SalesImport;
use App\Livewire\SalesTransaction;
use App\Livewire\TaxRow;
use App\Models\Atc;
use App\Models\Coa;
use App\Models\Contacts;
use App\Models\Payment;
use App\Models\TaxReturn;
use App\Models\TaxReturnTransaction;
use App\Models\TaxRow as ModelsTaxRow;
use App\Models\TaxType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Imagick;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionsController extends Controller
{
    private const VAT_RATE = 0.12; 
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $search = $request->input('search');
        $type = $request->input('type');
        $organizationId = session('organization_id'); 
        $perPage = $request->input('perPage', 5);
    
        $query = Transactions::with('contactDetails')
            ->where('organization_id', $organizationId);
    
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('contactDetails', function ($query) use ($search) {
                    $query->where('bus_name', 'like', "%{$search}%");
                })
                ->orWhere('inv_number', 'like', "%{$search}%")
                ->orWhere('transaction_type', 'like', "%{$search}%");
            });
        }
    
        if ($type && $type !== 'All') {
            $query->where('transaction_type', $type);
        }
    
        
        $transactions = $query->paginate($perPage);
    
        

        $purchaseCount = Transactions::where('organization_id', $organizationId)
            ->where('transaction_type', 'Purchase')
            ->count();

    
        $salesCount = Transactions::where('organization_id', $organizationId)
            ->where('transaction_type', 'Sales')
            ->count();
    
        $journalCount = Transactions::where('organization_id', $organizationId)
            ->where('transaction_type', 'Journal')
            ->count();
    
        
        $allTransactionsCount = Transactions::where('organization_id', $organizationId)->count();
    
       
        return view('transactions', compact('transactions', 'purchaseCount', 'salesCount', 'journalCount', 'allTransactionsCount'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get the type from the query string, default to 'sales'
        $transactionType = $request->query('type', 'purchase');

        // Pass the type to the view
        return view('transactions.create', compact('transactionType'));
    }
    public function getSalesTransactions(Request $request)
{
    // Get organization from session
    $organization = OrgSetup::find(session('organization_id'));
    $organizationStartDate = Carbon::parse($organization->start_date);

    $startDate = null;
    $endDate = null;

    // Retrieve year and month from the tax return
    $year = $request->taxReturnYear;  // Passed from the frontend
    $monthOrQuarter = $request->taxReturnMonth;  // Passed from the frontend

    // Determine the date range based on the tax return's year and month/quarter
    if (is_numeric($monthOrQuarter)) {
        // Month selection
        $startDate = Carbon::create($year, $monthOrQuarter, 1)->startOfMonth();
        $endDate = Carbon::create($year, $monthOrQuarter, 1)->endOfMonth();
    } else {
        // Quarter selection
        $startMonth = $organizationStartDate->month;

        if ($monthOrQuarter == 'Q1') {
            $startDate = Carbon::create($year, $startMonth, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else if ($monthOrQuarter == 'Q2') {
            $startDate = Carbon::create($year, $startMonth + 3, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else if ($monthOrQuarter == 'Q3') {
            $startDate = Carbon::create($year, $startMonth + 6, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else if ($monthOrQuarter == 'Q4') {
            $startDate = Carbon::create($year, $startMonth + 9, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        }
    }

    // Fetch transactions with type 'sales' within the calculated date range
    $transactions = Transactions::with("contactDetails")
    -> where('transaction_type', 'sales')
        ->where('organization_id', session('organization_id'))
        ->whereBetween('date', [$startDate, $endDate])
        ->get();

    return response()->json($transactions);
}
public function getAllTransactions(Request $request)
{
    // Get organization from session
    $organization = OrgSetup::find(session('organization_id'));
    $organizationStartDate = Carbon::parse($organization->start_date);

    $startDate = null;
    $endDate = null;

    // Retrieve year and month from the tax return
    $year = $request->taxReturnYear;  // Passed from the frontend
    $monthOrQuarter = $request->taxReturnMonth;  // Passed from the frontend

    // Determine the date range based on the tax return's year and month/quarter
    if (is_numeric($monthOrQuarter)) {
        // Month selection
        $startDate = Carbon::create($year, $monthOrQuarter, 1)->startOfMonth();
        $endDate = Carbon::create($year, $monthOrQuarter, 1)->endOfMonth();
    } else {
        // Quarter selection
        $startMonth = $organizationStartDate->month;

        if ($monthOrQuarter == 'Q1') {
            $startDate = Carbon::create($year, $startMonth, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else if ($monthOrQuarter == 'Q2') {
            $startDate = Carbon::create($year, $startMonth + 3, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else if ($monthOrQuarter == 'Q3') {
            $startDate = Carbon::create($year, $startMonth + 6, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        } else if ($monthOrQuarter == 'Q4') {
            $startDate = Carbon::create($year, $startMonth + 9, 1);
            $endDate = $startDate->copy()->addMonths(2)->endOfMonth();
        }
    }

    // Fetch transactions with type 'sales' within the calculated date range
    $transactions = Transactions::with("contactDetails")
        ->where('organization_id', session('organization_id'))
        ->whereBetween('date', [$startDate, $endDate])
        ->get();

    return response()->json($transactions);
}

    public function deactivate(Request $request)
    {
        // Get the tax_return_id and transaction_ids from the request
        $taxReturnId = $request->input('tax_return_id');
        $transactionIds = $request->input('ids');
        
        // Make sure the 'ids' is an array and not empty
        if (empty($transactionIds)) {
            return response()->json(['message' => 'No transactions selected'], 400);
        }

        // Find the specific tax return by ID
        $taxReturn = TaxReturn::findOrFail($taxReturnId); // Ensure the tax return exists

        // Detach the transactions from the tax_return_transactions pivot table
        $taxReturn->transactions()->detach($transactionIds);

        // Log the activity for each detached transaction
        foreach ($transactionIds as $transactionId) {
            activity('Transaction Management')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturnId,
                    'transaction_id' => $transactionId,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Transaction with ID {$transactionId} was archived from Tax Return ID {$taxReturnId}.");
        }

        // Return a success response
        return response()->json(['message' => 'Transactions archived successfully']);
    }

    public function deactivateTransaction(Request $request)
    {
        // Get the tax_return_id, transaction_ids, and activeTab from the request
        $taxReturnId = $request->input('tax_return_id');
        $transactionIds = $request->input('ids');
        $activeTab = $request->input('activeTab'); // Either 'individual' or 'spouse'
        
        // Make sure the 'ids' is an array and not empty
        if (empty($transactionIds)) {
            return response()->json(['message' => 'No transactions selected'], 400);
        }

        // Find the specific tax return by ID
        $taxReturn = TaxReturn::findOrFail($taxReturnId); // Ensure the tax return exists

        if ($activeTab === 'individual') {
            // Detach individual transactions
            $taxReturn->individualTransaction()->detach($transactionIds);

            // Log activity for individual transactions
            foreach ($transactionIds as $transactionId) {
                activity('Transaction Management')
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'tax_return_id' => $taxReturnId,
                        'transaction_id' => $transactionId,
                        'type' => 'individual',
                        'ip' => $request->ip(),
                        'browser' => $request->header('User-Agent'),
                    ])
                    ->log("Individual transaction with ID {$transactionId} was archived from Tax Return ID {$taxReturnId}.");
            }

        } elseif ($activeTab === 'spouse') {
            // Detach spouse transactions
            $taxReturn->spouseTransactions()->detach($transactionIds);

            // Log activity for spouse transactions
            foreach ($transactionIds as $transactionId) {
                activity('Transaction Management')
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'tax_return_id' => $taxReturnId,
                        'transaction_id' => $transactionId,
                        'type' => 'spouse',
                        'ip' => $request->ip(),
                        'browser' => $request->header('User-Agent'),
                    ])
                    ->log("Spouse transaction with ID {$transactionId} was archived from Tax Return ID {$taxReturnId}.");
            }

        } else {
            // If an invalid activeTab is passed
            return response()->json(['message' => 'Invalid activeTab specified'], 400);
        }
        
        // Return a success response
        return response()->json(['message' => 'Transactions archived successfully']);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function showUploadForm()
    {
        // Retrieve the necessary data from the database
        $organization_id = session('organization_id');  // Assuming organization_id is stored in the session
        
        // Fetch COA records based on the organization_id (if available) or null
        $coas = Coa::where('organization_id', $organization_id)->orWhereNull('organization_id')->get();
        
        // Fetch tax types with transaction_type as 'purchase'
        $tax_types = TaxType::where('transaction_type', 'purchase')->get();
        
        // Fetch tax codes with transaction_type as 'purchase'
        $tax_codes = Atc::where('transaction_type', 'purchase')->get();
        
        // Pass the data to the view
        return view('transactions.upload', compact('coas', 'tax_types', 'tax_codes'));
    }
    
    public function store(StoreTransactionsRequest $request)
    {
        //
    }
    public function addPercentage(Request $request)
    {
        // Validate the incoming request
      
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'tax_return_id' => 'required|exists:tax_returns,id',
        ]);

        // Get the active TaxReturn (you can modify this to get the "currently active" TaxReturn based on your logic)
        $taxReturn = TaxReturn::find($request->tax_return_id);

        if (!$taxReturn) {
            return redirect()->back()->with('error', 'Tax return not found.');
        }

        // Add the transaction to the tax return's tax_return_transactions table
        $taxReturnTransaction = new TaxReturnTransaction();
        $taxReturnTransaction->tax_return_id = $taxReturn->id;
        $taxReturnTransaction->transaction_id = $request->transaction_id;
        $taxReturnTransaction->save();

        // Return success message and redirect back
        return redirect()->back()->with('success', 'Transaction added to tax return successfully!');
    }
    public function addTransaction(Request $request)
    {
        $isSpouse = filter_var($request->input('is_spouse'), FILTER_VALIDATE_BOOLEAN);

        // Manually set the 'is_spouse' input to the boolean value
        $request->merge(['is_spouse' => $isSpouse]);
        
        // Validate the request
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'tax_return_id' => 'required|exists:tax_returns,id',
            'is_spouse' => 'required|boolean',
        ]);
        
        // Get the active TaxReturn
        $taxReturn = TaxReturn::find($request->tax_return_id);

        if (!$taxReturn) {
            return redirect()->back()->with('error', 'Tax return not found.');
        }

        // Check if the transaction is for an individual or spouse
        if ($request->is_spouse) {
            // Add the transaction to the spouse's transactions table
            $spouseTransaction = new SpouseTransaction();
            $spouseTransaction->tax_return_id = $taxReturn->id;
            $spouseTransaction->transaction_id = $request->transaction_id;
            $spouseTransaction->save();

            // Log activity
            activity('Transaction Management')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'transaction_id' => $request->transaction_id,
                    'type' => 'spouse',
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Transaction with ID {$request->transaction_id} was added to the spouse's transactions in Tax Return ID {$taxReturn->id}.");
        } else {
            // Add the transaction to the individual's transactions table
            $individualTransaction = new IndividualTransaction();
            $individualTransaction->tax_return_id = $taxReturn->id;
            $individualTransaction->transaction_id = $request->transaction_id;
            $individualTransaction->save();

            // Log activity
            activity('Transaction Management')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'transaction_id' => $request->transaction_id,
                    'type' => 'individual',
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Transaction with ID {$request->transaction_id} was added to the individual's transactions in Tax Return ID {$taxReturn->id}.");
        }

        // Return success message and redirect back
        return redirect()->back()->with('success', 'Transaction added successfully!');
    }

public function storeUpload(Request $request)
{
    // Validate the request using Laravel's built-in validation
    $validated = $request->validate([
        'vendor' => 'required|string|max:255',
        'customer_tin' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'organization_type' => 'required|string|in:Individual,Non-Individual',
        'city' => 'required|string|max:100',
        'zip_code' => 'required|string|max:10',
        'date' => 'required|date',
        'reference_number' => 'required|string|unique:transactions,reference',
        'amount' => 'required|numeric|min:0',
        'tax_amount' => 'required|numeric|min:0',
        'net_amount' => 'required|numeric|min:0',
        'description' => 'required|string|max:255',
        'tax_type' => 'required|exists:tax_types,id',
        'tax_code' => 'nullable|exists:atcs,id',
        'coa' => 'required|exists:coas,id'
    ]);

    try {
        DB::beginTransaction();
        
        // Create or find contact
        $contact = Contacts::firstOrCreate(
            [
                'bus_name' => $validated['vendor'],
                'contact_tin' => $validated['customer_tin']
            ],
            [
                'contact_address' => $validated['address'],
                'contact_type' => $validated['organization_type'],
                'contact_city' => $validated['city'],
                'contact_zip' => $validated['zip_code']
            ]
        );

        // Create transaction
        $transaction = Transactions::create([
            'date' => $validated['date'],
            'reference' => $validated['reference_number'],
            'total_amount' => $validated['amount'],
            'vat_amount' => $validated['tax_amount'],
            'vatable_purchase' => $validated['net_amount'],
            'transaction_type' => 'Purchase',
            'organization_id' => session('organization_id'),
            'contact' => $contact->id,
        ]);

        // Create tax row
        $transaction->taxRows()->create([
            'description' => $validated['description'],
            'tax_type' => $validated['tax_type'],
            'tax_code' => $validated['tax_code'],
            'coa' => $validated['coa'],
            'amount' => $validated['amount'],
            'tax_amount' => $validated['tax_amount'],
            'net_amount' => $validated['net_amount']
        ]);

        // Log activity
        activity('transactions')
            ->performedOn($transaction)
            ->causedBy(Auth::user())
            ->withProperties([
                'organization_id' => session('organization_id'),
                'attributes' => $transaction->toArray(),
            ])
            ->log('Transaction using upload was created');

        DB::commit();
        return redirect()->route('transactions')->with('success', 'Transaction saved successfully!');

    } catch (ValidationException $e) {
        return back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('uploaded_image', session('uploaded_image')); // Re-flash the image
    }
    catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withErrors(['error' => 'Failed to save transaction: ' . $e->getMessage()])
            ->withInput()
            ->with('uploaded_image', session('uploaded_image')); // Re-flash the image
    }
}


public function upload(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'receipt' => 'required|file|mimes:jpeg,png|max:5120' // 5MB max
        ]);

        // Process the file
        $path = $request->file('receipt')->store('transactions');
        $processedPath = $this->preprocessImage(storage_path("app/$path"));
        $extractedText = $this->extractTextFromReceipt($processedPath);
        
        // Clean and parse the extracted text
        $cleanedData = $this->cleanExtractedText($extractedText);

        // Log activity
        activity('Receipt Upload')
            ->causedBy(Auth::user())
            ->withProperties([
                'file_path' => $path,
                'processed_path' => $processedPath,
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
                'extracted_text_preview' => substr($extractedText, 0, 100), // Log a preview of the extracted text
            ])
            ->log('A receipt was uploaded and processed.');

        // Return back with success message and data
        // Store the cleaned data in the session
        session()->flash('cleanedData', $cleanedData);
        
        // Store the uploaded image in the session for display
        $imageData = base64_encode(file_get_contents($request->file('receipt')));
        $imageSrc = 'data:' . $request->file('receipt')->getMimeType() . ';base64,' . $imageData;
        session()->flash('uploaded_image', $imageSrc);
        
        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cleanedData' => $cleanedData
            ]);
        }
        
        // If it's a regular form submit, redirect back with the data
        return redirect()->back()->with([
            'success' => 'Receipt processed successfully',
            'cleanedData' => $cleanedData
        ]);

    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}


    private function cleanExtractedText($extractedText)

{
    // Basic cleanup: Remove unnecessary newlines and extra spaces
    $cleanedText = preg_replace('/\s+/', ' ', $extractedText);

    // Apply OCR error corrections
    $cleanedText = $this->cleanOcrText($cleanedText);

    // Extract vendor name
    preg_match("/([A-Za-z0-9\s\.\-]+(?:\s+[A-Za-z0-9\s\.\-]+)*)\s*[:,\n]/", $cleanedText, $vendor);
    $vendor = $vendor ? trim($vendor[1]) : 'Unknown Vendor';

    // Extract address
    preg_match("/([A-Za-z0-9\s\.\-\d]+)(?=\s*(?:TIN|Date|TOTAL|Cashier|Ref|Time|OfficialReceipt|Amount))/i", $cleanedText, $address);
    $address = $address ? trim($address[1]) : 'Unknown Address';

    // Extract TIN
    preg_match("/(?:MIN|TIN)[\s:\-]*([\d\-]+)/i", $cleanedText, $tin);
    $tin = $tin ? $tin[1] : 'N/A';

    // Extract date
    preg_match("/(?:Date[:\-\s]*|)(\d{1,4}[-\/]\d{1,2}[-\/]\d{1,4})/", $cleanedText, $date);
    $date = $date ? $date[1] : 'Unknown Date';

    // Extract invoice number
    preg_match("/(?:Invoice|Ref|SlF|OfficialReceipt)\s*[:\-]?\s*(\d{4,})/", $cleanedText, $invoiceNumber);
    $invoiceNumber = $invoiceNumber ? $invoiceNumber[1] : 'Unknown Invoice Number';

    // Extract amounts and clean them
    // Total Amount (VAT inclusive)
    preg_match("/TOTAL[\s\w]*\s*(?:PHP)?[\s]*([\d,\.]+)/", $cleanedText, $totalAmount);
    $totalAmount = $totalAmount ? $this->cleanAmount($totalAmount[1]) : null;

    // Tax Amount (VAT)
    preg_match("/(\d{1,2}%\s*VAT)\s*(?:PHP)?[\s]*([\d,\.]+)/i", $cleanedText, $taxAmount);
    $taxAmount = $taxAmount ? $this->cleanAmount($taxAmount[2]) : null;

    // Net Amount (VAT exclusive)
    preg_match("/(?:Change|Net)\s*[\s\-]*(?:PHP)?[\s]*([\d,\.]+)/", $cleanedText, $netAmount);
    $netAmount = $netAmount ? $this->cleanAmount($netAmount[1]) : null;

    // Calculate missing values based on what we have
    $calculatedAmounts = $this->calculateMissingAmounts($totalAmount, $taxAmount, $netAmount);

    return [
        'vendor' => $vendor,
        'tin' => $tin,
        'date' => $date,
        'invoice_number' => $invoiceNumber,
        'total_amount' => $calculatedAmounts['total_amount'],
        'tax_amount' => $calculatedAmounts['tax_amount'],
        'net_amount' => $calculatedAmounts['net_amount'],
        'address' => $address,
    ];
}

// Helper method to clean amount values
private function cleanAmount($amount)
{
    // Remove any currency symbols, spaces, and normalize decimal separator
    $cleaned = preg_replace('/[^0-9,\.]/', '', $amount);
    
    // Convert to standard decimal format
    $cleaned = str_replace(',', '', $cleaned);
    
    // Ensure proper decimal format
    return number_format((float)$cleaned, 2, '.', '');
}


    
    
    

    
private function preprocessImage($filePath)
{
    try {
        // Define the path for saving the final processed image in the public directory
        $basePath = storage_path('app/images/processed');

        // Create the directory if it doesn't exist
        if (!file_exists($basePath)) {
            mkdir($basePath, 0755, true);
        }

        // Load the image using Imagick
        $image = new Imagick($filePath);

        // Convert the image to PNG format for better OCR processing
        $image->setFormat('png');

        // Step 1: Resize the image to a reasonable width (e.g., 2000px)
        $optimalWidth = 2000;
        $currentWidth = $image->getImageWidth();
        if ($currentWidth != $optimalWidth) {
            $image->resizeImage($optimalWidth, 0, Imagick::FILTER_LANCZOS, 1);
        }

        // Step 2: Convert to grayscale
        $image->transformImageColorspace(Imagick::COLORSPACE_GRAY);

        // Step 3: Apply adaptive thresholding (binarization) to make text more defined
       

        // Step 4: Moderate contrast adjustment (without going too far)
        $image->modulateImage(100, 130, 100); // Slightly increase contrast (130 instead of 150)

        // Step 5: Apply slight sharpness enhancement (after contrast adjustments)
        $image->sharpenImage(0, 1); // Apply sharpening with low intensity

        // Step 6: Deskew the image if needed (with lower threshold)
        $image->deskewImage(2); // Use a lower threshold to avoid distortion

        // Final processed image path
        $processedPath = $basePath . '/' . uniqid('final_processed_') . '.png';
        $image->writeImage($processedPath);

        // Clear Imagick resources
        $image->clear();
        $image->destroy();

        // Return the path to the processed image
        return $processedPath;

    } catch (ImagickException $e) {
        throw new \Exception("Image preprocessing failed: " . $e->getMessage());
    }
}
private function cleanOcrText($text)
{
    // Regex-based replacements to fix common OCR errors
    $text = preg_replace('/Raillinghills/', 'Rollinghills', $text);
    $text = preg_replace('/Pho/', 'Php', $text);
    $text = preg_replace('/s-/', '-', $text);
    $text = preg_replace('/12k/', '12%', $text);

    // Optionally, add more regex replacements or spell-checking here

    return $text;
}


private function extractTextFromReceipt($filePath)
{
    try {
        // Use TesseractOCR PHP wrapper to process the image
        $text = (new TesseractOCR($filePath))
            ->lang('eng') // Specify language (e.g., English)
            ->psm(6) // Page Segmentation Mode: 6 = uniform block of text
            ->oem(1) // OCR Engine Mode: 1 = LSTM (best for receipts)
            ->dpi(300) // Optimal DPI for OCR (usually 300)
            ->config('tessedit_char_whitelist', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.$,%:/-') // Whitelist characters (letters, numbers, and symbols)
            ->run(); // Run OCR and get text

        // Log the OCR output text for debugging
        Log::info('OCR Output: ' . $text);

        // Clean the OCR output text
        $cleanText = $this->cleanOcrText($text);

        return $cleanText;
    } catch (\Exception $e) {
        // Handle exceptions and errors
        return 'OCR Processing Failed: ' . $e->getMessage();
    }
}



    

    

    private function extractTotalAmount($text)
{
    // Regex pattern to match the total amount, assuming it’s in the format like 'Total: $123.45'
    preg_match('/Total:\s*\$?([\d,]+\.\d{2})/', $text, $matches);

    return $matches[1] ?? 'Total not found';
}


    /**
     * Display the specified resource.
     */
    public function show(Transactions $transaction)
    {
        // Fetch associated tax rows for the transaction
        $taxRows = ModelsTaxRow::where('transaction_id', $transaction->id)->with('coaAccount')->get();
        $coas = coa::all();
        $tax_types = TaxType::all();
        $atcs = Atc::all();

        $contacts = Contacts::all()->map(function ($contact) {
            return [
                'id' => $contact->id,  // The value key
                'bus_name' => $contact->bus_name,  // The label key
                'contact_tin'=>$contact->contact_tin,
            ];
        })->toArray();

        // Access the transaction_type directly from the $transaction instance
        $transaction_type = $transaction->transaction_type;

        // Pass the transaction, tax rows, and transaction type to the view
        return view('transactions.show', compact('transaction', 'taxRows', 'contacts', 'atcs', 'tax_types', 'coas', 'transaction_type'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($transactionId)
    {
        $transaction = Transactions::with('contactDetails', 'taxRows')->findOrFail($transactionId);
        
        $type = $transaction->transaction_type;

        // Log activity
        activity('Transaction Management')
            ->causedBy(Auth::user())
            ->withProperties([
                'transaction_id' => $transactionId,
                'transaction_type' => $type,
                'ip' => request()->ip(),
                'browser' => request()->header('User-Agent'),
            ])
            ->log("Viewed edit form for Transaction ID {$transactionId}.");

        // Pass 'transaction_type' to the view
        return view('transactions.edit', compact('transaction', 'type'));
    }

    public function mark($transactionId)
    {
        $transaction = Transactions::findOrFail($transactionId);

        // Update the status to 'posted'
        $transaction->status = 'posted';
        $transaction->save();

        // Enhanced activity logging
        activity('Transaction Management')
            ->performedOn($transaction)
            ->causedBy(Auth::user())
            ->withProperties([
                'transaction_id' => $transaction->id,
                'organization_id' => session('organization_id'),
                'status' => 'posted',
                'attributes' => $transaction->toArray(),
                'ip' => request()->ip(),
                'browser' => request()->header('User-Agent'),
            ])
            ->log("Transaction {$transaction->inv_number} was marked as posted.");

        // Flash success message
        session()->flash('success', 'Transaction has been successfully marked as Posted.');

        // Redirect to the transaction show page, or any other relevant page
        return redirect()->route('transactions.show', ['transaction' => $transactionId]);
    }
    public function markAsPaid(Request $request, Transactions $transaction)
{
    // Validate payment data
    $validated = $request->validate([
        'payment_date' => 'required|date',
        'reference_number' => 'required|string|max:255',
      
        'total_amount_paid' => 'required|numeric|min:0', // Optional field for partial payments
    ]);

    // Create a new payment record
    $payment = Payment::create([
        'transaction_id' => $transaction->id,
        'payment_date' => $validated['payment_date'],
        'reference_number' => $validated['reference_number'],
        'total_amount_paid' => $validated['total_amount_paid'],  // Amount paid
    ]);

    // Update the transaction's status based on whether it's fully or partially paid
    $totalPaid = Payment::where('transaction_id', $transaction->id)->sum('total_amount_paid');
    if ($totalPaid >= $transaction->total_amount) {
        $transaction->Paidstatus = 'Paid';  // Fully paid
    } else {
        $transaction->Paidstatus = 'Partially Paid';  // Partial payment
    }
    $transaction->save();

    activity('transactions')
        ->performedOn($transaction)
        ->causedBy(Auth::user())
        ->withProperties([
            'organization_id' => session('organization_id'),
            'attributes' => $transaction->toArray(),
        ])
        ->log("Transaction {$transaction->inv_number} was mark as paid");


    session()->flash('successPayment', 'Transaction has been successfully marked as Posted.');
    // Return a JSON response indicating success
    return response()->json(['successPayment' => true]);
}

    
    
    
    public function update(Request $request, $id)
    {
        $transaction = Transactions::findOrFail($id);
        
        // Validate input data
        $validated = $request->validate([
            'date' => 'required|date',
            'inv_number' => 'required|string',
            'reference' => 'nullable|string',
            'total_amount' => 'required|numeric',
            // Add other fields as needed
        ]);
        
        // Track the original attributes before updating
        $originalAttributes = $transaction->getOriginal();
        
        // Update transaction with validated data
        $transaction->update($validated);
        
        // Log activity
        activity('Transaction Management')
            ->performedOn($transaction)
            ->causedBy(Auth::user())
            ->withProperties([
                'transaction_id' => $transaction->id,
                'changes' => [
                    'before' => $originalAttributes,
                    'after' => $transaction->getAttributes(),
                ],
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log("Transaction {$transaction->inv_number} was updated.");
        
        // Redirect to the transaction show page with a success message
        return redirect()->route('transactions.show', $transaction->id)->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        
        $ids = $request->input('ids');  // This will be an array of IDs sent from JavaScript

        $transactions = Transactions::whereIn('id', $ids)->get();

        // Log the incoming IDs for debugging
        Log::info('Received transaction IDs: ', $ids);
        Transactions::whereIn('id', $ids)->delete();

        // Log the deletions manually
        foreach ($transactions as $transaction) {
            activity('transactions')
                ->performedOn($transaction)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => session('organization_id'),
                    'attributes' => $transaction->toArray(),
                ])
                ->log("Transaction {$transaction->inv_number} was soft deleted");
        }

        // Optionally, return a response
        return response()->json(['message' => 'Transaction soft deleted successfully.']);
        //
    }

    public function import(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Import the file
        Excel::import(new SalesImport, $file);

        // Log activity
        activity('Transaction Import')
            ->causedBy(Auth::user())
            ->withProperties([
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log('Transactions were imported from a file.');

        // Return back with success message
        return back()->with('success', 'Transactions imported successfully!');
    }

    public function importPreview(Request $request)
    {
        // Validate the file input
        $request->validate([                    
            'file' => 'required|mimes:csv,txt'
        ]);

        // Load the CSV data but don't save it to the database yet
        $data = Excel::toArray(new SalesImport, $request->file('file'))[0];

        // Pass the data to the view to preview
        return view('transactions.preview', compact('data'));
    }
    public function download_transaction()
    {
        // Get the organization ID from the session
        $organizationId = session('organization_id');
        
        // Fetch all transactions for the specific organization, with related contact details and tax rows
        $transactions = Transactions::with(['contactDetails', 'taxRows'])
                                    ->where('organization_id', $organizationId)
                                    ->whereIn('transaction_type', ['Sales', 'Purchase'])
                                    ->get();

        // Check if transactions exist, if not, handle the case
        if ($transactions->isEmpty()) {
            return back()->with('alert', 'No transactions found for this organization.');
        }

        // Log activity
        activity('Transaction Download')
            ->causedBy(Auth::user())
            ->withProperties([
                'organization_id' => $organizationId,
                'transaction_count' => $transactions->count(),
                'ip' => request()->ip(),
                'browser' => request()->header('User-Agent'),
            ])
            ->log('Downloaded transactions as a PDF.');

        // Generate the PDF
        $pdf = PDF::loadView('transactions.pdf', compact('transactions'));

        // Download the PDF with a specific name
        return $pdf->download('transactions_list.pdf');
    }
   
    private function calculateMissingAmounts($totalAmount, $taxAmount, $netAmount) {
        $vatRate = 0.12;
        
        // Convert string values to floats for calculation
        $totalAmount = $totalAmount ? (float)$totalAmount : null;
        $taxAmount = $taxAmount ? (float)$taxAmount : null;
        $netAmount = $netAmount ? (float)$netAmount : null;
    
        // If we have total amount only
        if ($totalAmount && !$taxAmount && !$netAmount) {
            $taxAmount = round($totalAmount - ($totalAmount / (1 + $vatRate)), 2);
            $netAmount = $totalAmount - $taxAmount;
        }
        // If we have net amount only
        elseif (!$totalAmount && !$taxAmount && $netAmount) {
            $taxAmount = round($netAmount * $vatRate, 2);
            $totalAmount = $netAmount + $taxAmount;
        }
        // If we have tax amount only
        elseif (!$totalAmount && $taxAmount && !$netAmount) {
            $netAmount = round($taxAmount / $vatRate, 2);
            $totalAmount = $netAmount + $taxAmount;
        }
        // If we have total and tax amount
        elseif ($totalAmount && $taxAmount && !$netAmount) {
            $netAmount = $totalAmount - $taxAmount;
        }
        // If we have total and net amount
        elseif ($totalAmount && !$taxAmount && $netAmount) {
            $taxAmount = $totalAmount - $netAmount;
        }
        // If we have tax and net amount
        elseif (!$totalAmount && $taxAmount && $netAmount) {
            $totalAmount = $netAmount + $taxAmount;
        }
        // If we don't have any amounts, set defaults
        elseif (!$totalAmount && !$taxAmount && !$netAmount) {
            $totalAmount = '0.00';
            $taxAmount = '0.00';
            $netAmount = '0.00';
        }
    
        // Format all amounts to 2 decimal places
        return [
            'total_amount' => number_format($totalAmount, 2, '.', ''),
            'tax_amount' => number_format($taxAmount, 2, '.', ''),
            'net_amount' => number_format($netAmount, 2, '.', '')
        ];
    }
}
