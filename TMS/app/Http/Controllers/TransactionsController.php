<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Http\Requests\StoreTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Livewire\TaxRow;
use App\Models\atc;
use App\Models\coa;
use App\Models\Contacts;
use App\Models\TaxRow as ModelsTaxRow;
use App\Models\TaxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;


class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $search = $request->input('search');
        $type = $request->input('type');
        $organizationId = session('organization_id'); // Retrieve the organization_id from the session
    
        $query = Transactions::with('contactDetails')
            ->where('organization_id', $organizationId); // Filter by organization_id
    
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
    
        $transactions = $query->paginate(5);
    
        return view('transactions', compact('transactions'));
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
        return view('transactions.upload');
    }
    public function store(StoreTransactionsRequest $request)
    {
        //
    }
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Store the uploaded file
        $path = $request->file('receipt')->store('transactions');

        // Extract text using Tesseract OCR
        $extractedText = $this->extractTextFromReceipt(storage_path("app/$path"));

        // Process and return extracted text (or save to the database)
        return back()->with([
            'success' => 'Receipt uploaded successfully!',
            'extractedText' => $extractedText,
            'file_path' => $path // Optional, if you want to display the uploaded image
        ]);
    }
    private function extractTextFromReceipt($filePath)
    {
        // Use TesseractOCR PHP wrapper to process the image
        $text = (new TesseractOCR($filePath))
                ->lang('eng') // Specify language (e.g., English)
                ->run();

        return $text;
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
        $taxRows = ModelsTaxRow::where('transaction_id', $transaction->id)->get();
    

        // Pass the transaction and tax rows to the view
        return view('transactions.show', compact('transaction', 'taxRows'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateTransactionsRequest $request, Transactions $transactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transactions $transactions)
    {
        //
    }
}
