<?php

namespace App\Http\Controllers;

use App\Models\OrgSetup;
use App\Models\Transactions;
use App\Http\Requests\StoreTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Imports\SalesImport;
use App\Livewire\SalesTransaction;
use App\Livewire\TaxRow;
use App\Models\atc;
use App\Models\coa;
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

class TransactionsController extends Controller
{
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
        $taxReturn = TaxReturn::findOrFail($taxReturnId);  // Ensure the tax return exists
        
        // Detach the transactions from the tax_return_transactions pivot table
        $taxReturn->transactions()->detach($transactionIds);
        
        // Optionally, you can return a success response
        return response()->json(['message' => 'Transactions archived successfully']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function showUploadForm()
    {
        // Retrieve the necessary data from the database
        $coas = Coa::all(); 
        $tax_types = TaxType::all();
        $tax_codes = Atc::all();
    
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
    public function storeUpload(Request $request)
{
 
    // Step 1: Create or find a Contact
    $contact = Contacts::firstOrCreate(
        [
            'bus_name' => $request->vendor, 
            'contact_tin' => $request->customer_tin
        ],
        [
            'contact_address' => $request->address,
            'contact_type' => $request->organization_type,  // Use the submitted 'organization_type' value
            'contact_city' => $request->city,
            'contact_zip' => $request->zip_code  // Add 'city' field to the contact
        ]
    );

    // Step 2: Create the Transaction
    $transaction = Transactions::create([
        'date' => $request->date,
        'reference' => $request->reference_number,
        'total_amount' => $request->amount,
        'vat_amount' => $request->tax_amount,  // tax_amount goes to vat_amount
        'vatable_purchase' => $request->net_amount, // net_amount goes to vatable_purchase
        'transaction_type'=> 'Purchase',
        'organization_id' => session('organization_id') ,
        'contact' => $contact->id,
        
    ]);

    // Step 3: Add a TaxRow for each item in the transaction
    $taxRow = new ModelsTaxRow([
        'description' => $request->description,
        'tax_type' => $request->tax_type,
        'tax_code' => $request->tax_code,
        'coa' => $request->coa,
        'amount' => $request->amount,
        'tax_amount' => $request->tax_amount,
        'net_amount' => $request->net_amount
    ]);

    // Associate the TaxRow with the Transaction
    $transaction->taxRows()->save($taxRow);

    // Redirect back with a success message
    return redirect()->route('transactions')->with('success', 'Transaction and tax rows saved successfully!');
}


    public function upload(Request $request)
    {
        // Validate and process the file
        $path = $request->file('receipt')->store('transactions');
        $processedPath = $this->preprocessImage(storage_path("app/$path"));
        $extractedText = $this->extractTextFromReceipt($processedPath);
        
        // Clean and parse the extracted text
        $cleanedData = $this->cleanExtractedText($extractedText);
        
        return back()->with([
            'success' => 'Receipt uploaded and processed successfully!',
            'cleanedData' => $cleanedData,  // Pass the cleaned data to the view
            'uncleanData' => $extractedText,
        ]);
    }
    private function cleanExtractedText($extractedText)
{
    // Basic cleanup: Remove unnecessary newlines and extra spaces
    $cleanedText = preg_replace('/\s+/', ' ', $extractedText);

    // Apply OCR error corrections (basic example)
    $cleanedText = $this->cleanOcrText($cleanedText);

    // DEBUG: Output cleaned text to inspect what's happening
    // echo $cleanedText;

    // Extract Vendor Name: We capture the first valid part of the text, stopping when we reach a section indicating address or receipt labels
    preg_match("/([A-Za-z0-9\s\.\-]+(?:\s+[A-Za-z0-9\s\.\-]+)*)\s*[:,\n]/", $cleanedText, $vendor);

    $vendor = $vendor ? trim($vendor[1]) : 'Unknown Vendor';
    

  

    // Extract Address: This regex captures the next meaningful block of text after the vendor name, but stops before other structured information like TIN, Date, etc.
    preg_match("/([A-Za-z0-9\s\.\-\d]+)(?=\s*(?:TIN|Date|TOTAL|Cashier|Ref|Time|OfficialReceipt|Amount))/i", $cleanedText, $address);
    $address = $address ? trim($address[1]) : 'Unknown Address';

    // Extract TIN (look for TIN patterns)
    preg_match("/(?:MIN|TIN)[\s:\-]*([\d\-]+)/i", $cleanedText, $tin);
    $tin = $tin ? $tin[1] : 'N/A';
    // Extract Date (support both slash and hyphen date formats)
    preg_match("/(?:Date[:\-\s]*|)(\d{1,4}[-\/]\d{1,2}[-\/]\d{1,4})/", $cleanedText, $date);
    $date = $date ? $date[1] : 'Unknown Date';


    // Extract Invoice Number (commonly starts with Invoice, Ref, or other labels)
    preg_match("/(?:Invoice|Ref|SlF|OfficialReceipt)\s*[:\-]?\s*(\d{4,})/", $cleanedText, $invoiceNumber);
    $invoiceNumber = $invoiceNumber ? $invoiceNumber[1] : 'Unknown Invoice Number';

    // Extract Total Amount (look for 'TOTAL', 'TOTALAMOUNT')
    preg_match("/TOTAL[\s\w]*\s*(PHP[\d,\.]+)/", $cleanedText, $totalAmount);
    $totalAmount = $totalAmount ? $totalAmount[1] : '0.00';

    // Extract Tax Amount (for VAT-related terms)
    preg_match("/(\d{1,2}%\s*VAT)\s*([\w]*[\d,\.]+)/i", $cleanedText, $taxAmount);
    $taxAmount = $taxAmount ? $taxAmount[2] : '0.00';


    // Extract Net Amount (from 'Change' or 'Net' terms)
    preg_match("/(?:Change|Net)\s*[\s\-]*([\d,\.]+)/", $cleanedText, $netAmount);
    $netAmount = $netAmount ? 'PHP' . $netAmount[1] : 'PHP0.00';

    // Return the extracted and cleaned information as an array
    return [
        'vendor' => $vendor,
        'tin' => $tin,
        'date' => $date,
        'invoice_number' => $invoiceNumber,
        'total_amount' => $totalAmount,
        'tax_amount' => $taxAmount,
        'net_amount' => $netAmount,
        'address' => $address,
    ];
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

        // Pass the transaction and tax rows to the view
        return view('transactions.show', compact('transaction', 'taxRows', 'contacts','atcs','tax_types','coas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($transactionId)
    {
        $transaction = Transactions::with('contactDetails', 'taxRows')->findOrFail($transactionId);
       
        $type = $transaction->transaction_type;
      
        // Pass 'transaction_type' to the view
        return view('transactions.edit', compact('transaction', 'type'));
    }
    public function mark($transactionId)
    {
        $transaction = Transactions::findOrFail($transactionId);

        // Update the status to 'posted'
        $transaction->status = 'posted';
        $transaction->save();

        // Optionally, you can return a response or redirect to another page
        session()->flash('success', 'Transaction has been successfully marked as Posted.');
        
        // You can redirect to the transaction show page, or wherever needed
        return redirect()->route('transactions.show', ['transaction' => $transactionId]);
    }
    public function markAsPaid(Request $request, Transactions $transaction)
{
    // Validate payment data
    $validated = $request->validate([
        'payment_date' => 'required|date',
        'reference_number' => 'required|string|max:255',
        'bank_account' => 'required|string|max:255',
        'total_amount_paid' => 'required|numeric|min:0', // Optional field for partial payments
    ]);

    // Create a new payment record
    $payment = Payment::create([
        'transaction_id' => $transaction->id,
        'payment_date' => $validated['payment_date'],
        'reference_number' => $validated['reference_number'],
        'bank_account' => $validated['bank_account'],
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
    
        // Update transaction with validated data
        $transaction->update($validated);
    
        // Optionally, handle any additional fields, such as tax rows
    
        return redirect()->route('transactions.show', $transaction->id)->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        
        $ids = $request->input('ids');  // This will be an array of IDs sent from JavaScript

        // Log the incoming IDs for debugging
        Log::info('Received transaction IDs: ', $ids);
        Transactions::whereIn('id', $ids)->delete();

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

        // Import the file
        Excel::import(new SalesImport, $request->file('file'));

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
                                    ->get();
    
        // Check if transactions exist, if not, handle the case
        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'No transactions found for this organization.'], 404);
        }
    
        // Generate the PDF
        $pdf = PDF::loadView('transactions.pdf', compact('transactions'));
    
        // Download the PDF with a specific name
        return $pdf->download('transactions_list.pdf');
    }
   

}
