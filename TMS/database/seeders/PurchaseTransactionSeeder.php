<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transactions;
use App\Models\TaxRow;
use App\Models\TaxType;
use App\Models\Contacts;
use App\Models\Atc;
use App\Models\Coa;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PurchaseTransactionSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Get necessary models for relationships
        $contacts = Contacts::all();
        $taxTypes = TaxType::where('transaction_type', 'purchase')->get();
        $vatTaxTypes = $taxTypes->where('VAT', 12); // Get tax types with 12% VAT
        $nonVatTaxTypes = $taxTypes->where('VAT', 0); // Get tax types with 0% VAT
        $atcs = Atc::where('transaction_type', 'purchase')->get();
        $coas = Coa::where('status', 'Active')->get();
        
        // Create 50 purchase transactions
        for ($i = 0; $i < 50; $i++) {
            // Create transaction
            $transaction = Transactions::create([
                'transaction_type' => 'Purchase',
                'date' => Carbon::now()->subDays($faker->numberBetween(1, 365)),
                'contact' => $contacts->random()->id,
                'inv_number' => 'INV-' . $faker->unique()->numberBetween(1000, 9999),
                'reference' => 'REF-' . $faker->unique()->numberBetween(1000, 9999),
                'status' => $faker->randomElement(['Draft', 'Posted']),
                'organization_id' => 1,
            ]);

            $totalVatableAmount = 0;
            $totalNonVatableAmount = 0;
            $totalVatAmount = 0;
            $totalAtcAmount = 0;

            // Generate 1-5 tax rows per transaction
            $numRows = $faker->numberBetween(1, 5);
            
            for ($j = 0; $j < $numRows; $j++) {
                // Randomly decide if this row is VATable
                $isVatRow = $faker->boolean(70); // 70% chance of VAT row
                
                // Select appropriate tax type
                $taxType = $isVatRow ? 
                    $vatTaxTypes->random() : 
                    $nonVatTaxTypes->random();

                // Generate amount
                $grossAmount = round($faker->randomFloat(2, 1000, 20000), 2);
                
                if ($isVatRow) {
                    // For VAT rows, calculate net amount and VAT
                    $netAmount = round($grossAmount / 1.12, 2);
                    $vatAmount = $grossAmount - $netAmount;
                    $totalVatableAmount += $netAmount;
                    $totalVatAmount += $vatAmount;
                } else {
                    // For non-VAT rows
                    $netAmount = $grossAmount;
                    $vatAmount = 0;
                    $totalNonVatableAmount += $netAmount;
                }

                // Randomly apply ATC
                $atcAmount = 0;
                $atc = null;
                if ($faker->boolean(60)) { // 60% chance of having ATC
                    $atc = $atcs->random();
                    $atcAmount = round($netAmount * ($atc->tax_rate / 100), 2);
                    $totalAtcAmount += $atcAmount;
                }

                // Create tax row
                TaxRow::create([
                    'transaction_id' => $transaction->id,
                    'description' => $faker->sentence(3),
                    'amount' => $grossAmount,
                    'tax_code' => $atc ? $atc->id : null,
                    'tax_type' => $taxType->id,
                    'tax_amount' => $vatAmount,
                    'atc_amount' => $atcAmount,
                    'net_amount' => $netAmount,
                    'coa' => $coas->random()->id,
                ]);
            }

            // Update transaction with calculated totals
            $totalAmount = $totalVatableAmount + $totalNonVatableAmount + $totalVatAmount - $totalAtcAmount;
            
            $transaction->update([
                'total_amount' => round($totalAmount, 2),
                'vatable_purchase' => round($totalVatableAmount, 2),
                'non_vatable_purchase' => round($totalNonVatableAmount, 2),
                'vat_amount' => round($totalVatAmount, 2)
            ]);
        }
    }
}