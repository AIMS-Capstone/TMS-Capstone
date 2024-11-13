<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Http\Requests\StoreTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Imports\SalesImport;
use App\Livewire\SalesTransaction;
use App\Livewire\TaxRow;
use App\Models\atc;
use App\Models\coa;
use App\Models\Contacts;
use App\Models\TaxRow as ModelsTaxRow;
use App\Models\TaxType;
use Barryvdh\DomPDF\Facade\Pdf;
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
    

        // Pass the transaction and tax rows to the view
        return view('transactions.show', compact('transaction', 'taxRows'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaction = Transactions::with('contactDetails', 'taxRows')->findOrFail($id);
        return view('livewire.edit-sales-transaction', compact('transaction'));
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
