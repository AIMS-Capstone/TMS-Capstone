<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transactions;
use App\Models\TaxRow;
use App\Models\TaxType;
use App\Models\Atc;
use App\Models\Coa;
use App\Models\Contacts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // First verify we have the required data
        $taxTypes = TaxType::where('transaction_type', 'sales')->get();
        if ($taxTypes->isEmpty()) {
            Log::error('No tax types found for sales transactions');
            return;
        }

        // Get tax type IDs with validation
        $vatSalesTaxType = $taxTypes->where('short_code', 'VOS')->first();
        $ptSalesTaxType = $taxTypes->where('short_code', 'PT')->first();

        if (!$vatSalesTaxType || !$ptSalesTaxType) {
            Log::error('Missing required tax types');
            return;
        }

        // Convert to IDs
        $vatSalesTaxTypeId = $vatSalesTaxType->id;
        $ptSalesTaxTypeId = $ptSalesTaxType->id;
        
        // Define ATC ID ranges
        $vatAtcIds = range(1, 38);
        $ptAtcIds = range(39, 60);

        // Get other required data
        $coaIds = Coa::where('status', 'Active')->pluck('id')->toArray();
        $contacts = Contacts::pluck('id')->toArray();

        if (empty($coaIds) || empty($contacts)) {
            Log::error('Missing required COA or contacts data');
            return;
        }

        // Create 5 sample transactions
        for ($i = 1; $i <= 5; $i++) {
            $date = Carbon::now()->subDays(rand(1, 30));
            
            try {
                // Create the main transaction
                $transaction = Transactions::create([
                    'transaction_type' => 'Sales',
                    'date' => $date,
                    'contact' => $contacts[array_rand($contacts)],
                    'inv_number' => 'INV-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'reference' => 'REF-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'organization_id' => 1,
                    'status' => 'Draft',
                    'total_amount' => 0,
                    'vatable_sales' => 0,
                    'vat_amount' => 0,
                    'non_vatable_sales' => 0
                ]);

                // 1 row per transaction
                $numRows = 1;
                $totalAmount = 0;
                $vatableSales = 0;
                $vatAmount = 0;
                $nonVatableSales = 0;

                for ($j = 1; $j <= $numRows; $j++) {
                    // Randomly select tax type
                    $taxTypeChoices = [
                        $vatSalesTaxTypeId => 'VOS',
                        $ptSalesTaxTypeId => 'PT',
                    ];
                    
                    $taxTypeId = array_rand($taxTypeChoices);
                    $amount = rand(1000, 10000);
                    $taxCode = null;
                    $atcAmount = 0;
                    
                    // Get the tax type
                    $taxType = $taxTypes->find($taxTypeId);
                    if (!$taxType) continue;

                    // Calculate amounts based on tax type's short code
                    if ($taxType->short_code === 'VOS') {
                        $netAmount = $amount / 1.12;
                        $taxAmount = $amount - $netAmount;
                        $vatableSales += $netAmount;
                        $vatAmount += $taxAmount;
                        
                        // Select random VAT ATC
                        $taxCode = $vatAtcIds[array_rand($vatAtcIds)];
                    } 
                    elseif ($taxType->short_code === 'PT') {
                        $netAmount = $amount;
                        $taxAmount = 0;
                        $nonVatableSales += $amount;
                        
                        // Calculate PT amount
                        $taxCode = $ptAtcIds[array_rand($ptAtcIds)];
                        $selectedAtc = Atc::find($taxCode);
                        if ($selectedAtc) {
                            $atcAmount = $amount * ($selectedAtc->tax_rate / 100);
                            $amount += $atcAmount;
                        }
                    }
                    else {
                        $netAmount = $amount;
                        $taxAmount = 0;
                        $nonVatableSales += $amount;
                    }

                    // Create tax row
                    TaxRow::create([
                        'transaction_id' => $transaction->id,
                        'description' => 'Sample Item ' . $j,
                        'amount' => $amount,
                        'tax_code' => $taxCode,
                        'tax_type' => $taxTypeId,
                        'tax_amount' => $taxAmount,
                        'net_amount' => $netAmount,
                        'coa' => $coaIds[array_rand($coaIds)],
                        'atc_amount' => $atcAmount
                    ]);

                    $totalAmount += $amount;
                }

                // Update transaction with calculated totals
                $transaction->update([
                    'total_amount' => $totalAmount,
                    'vatable_sales' => $vatableSales,
                    'vat_amount' => $vatAmount,
                    'non_vatable_sales' => $nonVatableSales
                ]);

            } catch (\Exception $e) {
                Log::error('Error creating transaction: ' . $e->getMessage());
                continue;
            }
        }
    }
}