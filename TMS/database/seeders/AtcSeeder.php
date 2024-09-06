<?php

namespace Database\Seeders;

use App\Models\atc;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AtcSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Sales Data
            ['tax_code' => 'VQ010', 'transaction_type' => 'sales', 'category' => 'Mining and Quarrying', 'coverage' => '', 'description' => 'VAT on mining and quarrying', 'tax_rate' => 12.00],
            ['tax_code' => 'VM040', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Tobacco', 'description' => 'VAT on manufacturing of Tobacco', 'tax_rate' => 12.00],
            ['tax_code' => 'VM110', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Alcohol', 'description' => 'VAT on manufacturing of Alcohol', 'tax_rate' => 12.00],
            ['tax_code' => 'VM120', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Petroleum', 'description' => 'VAT on manufacturing of Petroleum', 'tax_rate' => 12.00],
            ['tax_code' => 'VM130', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Automobiles', 'description' => 'VAT on manufacturing of Automobiles', 'tax_rate' => 12.00],
            ['tax_code' => 'VM140', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Non-Essentials (Excisable Goods)', 'description' => 'VAT on manufacturing of Non-Essentials (Excisable Goods)', 'tax_rate' => 12.00],
            ['tax_code' => 'VM030', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Cement', 'description' => 'VAT on manufacturing of Cement', 'tax_rate' => 12.00],
            ['tax_code' => 'VM020', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Food Products and Beverages', 'description' => 'VAT on manufacturing of Food Products and Beverages', 'tax_rate' => 12.00],
            ['tax_code' => 'VM150', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Pharmaceuticals', 'description' => 'VAT on manufacturing of Pharmaceuticals', 'tax_rate' => 12.00],
            ['tax_code' => 'VM050', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Flour', 'description' => 'VAT on manufacturing of Flour', 'tax_rate' => 12.00],
            ['tax_code' => 'VM160', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Sugar', 'description' => 'VAT on manufacturing of Sugar', 'tax_rate' => 12.00],
            ['tax_code' => 'VM100', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Pesticides', 'description' => 'VAT on manufacturing of Pesticides', 'tax_rate' => 12.00],
            ['tax_code' => 'VM010', 'transaction_type' => 'sales', 'category' => 'Manufacturing', 'coverage' => 'Others(General)', 'description' => 'VAT on manufacturing of Others (General)', 'tax_rate' => 12.00],
            ['tax_code' => 'VB113', 'transaction_type' => 'sales', 'category' => 'Non Life Insurance', 'coverage' => '', 'description' => 'VAT on non-life insurance companies', 'tax_rate' => 12.00],
            ['tax_code' => 'VB102', 'transaction_type' => 'sales', 'category' => 'Lending Investors / Dealer In securities / Pawnshops / Pre-need Co.', 'coverage' => '', 'description' => 'VAT on business service-dealers on sec/lending investors', 'tax_rate' => 12.00],
            ['tax_code' => 'VC010', 'transaction_type' => 'sales', 'category' => 'Construction', 'coverage' => '', 'description' => 'VAT on constructions', 'tax_rate' => 12.00],
            ['tax_code' => 'VT010', 'transaction_type' => 'sales', 'category' => 'Wholesale and Retail', 'coverage' => '', 'description' => 'VAT on wholesale and retail', 'tax_rate' => 12.00],
            ['tax_code' => 'VB100', 'transaction_type' => 'sales', 'category' => 'Hotel and Restaurants', 'coverage' => 'Hotels, Motels', 'description' => 'VAT on business services-hotel, motels, etc.', 'tax_rate' => 12.00],
            ['tax_code' => 'VB101', 'transaction_type' => 'sales', 'category' => 'Hotel and Restaurants', 'coverage' => 'Restaurants, Caterers', 'description' => 'VAT on restaurants, caterers business services- restaurants, caterers', 'tax_rate' => 12.00],
            ['tax_code' => 'VB105', 'transaction_type' => 'sales', 'category' => 'Transport Storage and Communications', 'coverage' => 'Land Transport-Cargo', 'description' => 'VAT on common carriers-land based (Road Freight)', 'tax_rate' => 12.00],
            ['tax_code' => 'VB106', 'transaction_type' => 'sales', 'category' => 'Transport Storage and Communications', 'coverage' => 'Water Transport-Cargo (Domestic Ocean Going Vessels)', 'description' => 'VAT on common carriers-domestic ocean-vessels going vessel', 'tax_rate' => 12.00],
            ['tax_code' => 'VB107', 'transaction_type' => 'sales', 'category' => 'Transport Storage and Communications', 'coverage' => 'Water Transport-Cargo (Inner island Shipping vessels)', 'description' => 'VAT on common carriers inter-island shipping vessels', 'tax_rate' => 12.00],
            ['tax_code' => 'VB108', 'transaction_type' => 'sales', 'category' => 'Transport Storage and Communications', 'coverage' => 'Air Transport-Cargo', 'description' => 'VAT on common carriers-aircraft', 'tax_rate' => 12.00],
            ['tax_code' => 'VB109', 'transaction_type' => 'sales', 'category' => 'Transport Storage and Communications', 'coverage' => 'Telephone and Telegraph', 'description' => 'VAT on franchise holders-telephone', 'tax_rate' => 12.00],
            ['tax_code' => 'VB111', 'transaction_type' => 'sales', 'category' => 'Transport Storage and Communications', 'coverage' => 'Radio / TV Broadcasting', 'description' => 'VAT on franchise holders-radio telephone broadcasting', 'tax_rate' => 12.00],
            ['tax_code' => 'VB112', 'transaction_type' => 'sales', 'category' => 'Other Franchise', 'coverage' => '', 'description' => 'VAT on franchise holders/others', 'tax_rate' => 12.00],
            ['tax_code' => 'VP100', 'transaction_type' => 'sales', 'category' => 'Real Estate, Renting and Business Activity', 'coverage' => 'Sale of Real Property', 'description' => 'VAT on sale of real property', 'tax_rate' => 12.00],
            ['tax_code' => 'VP101', 'transaction_type' => 'sales', 'category' => 'Real Estate, Renting and Business Activity', 'coverage' => 'Lease of Real Property', 'description' => 'VAT on lease of real property', 'tax_rate' => 12.00],
            ['tax_code' => 'VP102', 'transaction_type' => 'sales', 'category' => 'Real Estate, Renting and Business Activity', 'coverage' => 'Sale/Lease of intangible property', 'description' => 'VAT on sale/lease of intangible property', 'tax_rate' => 12.00],
            ['tax_code' => 'VD010', 'transaction_type' => 'sales', 'category' => 'Compulsory Social Security Public Administration and Defense', 'coverage' => '', 'description' => 'Compulsory Social Security Public Administration and Defense', 'tax_rate' => 12.00],
            ['tax_code' => 'VH010', 'transaction_type' => 'sales', 'category' => 'Other Community Social and Personal Service Activity', 'coverage' => '', 'description' => 'VAT on community, personal and household services', 'tax_rate' => 12.00],
            ['tax_code' => 'VS010', 'transaction_type' => 'sales', 'category' => 'Others', 'coverage' => 'Storage and Warehousing', 'description' => 'VAT on storage and warehousing', 'tax_rate' => 12.00],
            ['tax_code' => 'VB010', 'transaction_type' => 'sales', 'category' => 'Others', 'coverage' => 'Business Services (in general)', 'description' => 'VAT on business service in general', 'tax_rate' => 12.00],
            ['tax_code' => 'VI010', 'transaction_type' => 'sales', 'category' => 'Others', 'coverage' => 'Importation of Goods', 'description' => 'VAT on importation of goods', 'tax_rate' => 12.00],
            ['tax_code' => 'VS062', 'transaction_type' => 'sales', 'category' => 'Others', 'coverage' => '', 'description' => 'ON SERVICES RENDERED BY PERSONS ENGAGED IN THE PRACTICE OF PROFESSION OR CALLING AND PROFESSIONAL SERVICES RENDERED BY GENERAL PROFESSIONAL PARTNERSHIPS', 'tax_rate' => 12.00],
            ['tax_code' => 'VS210', 'transaction_type' => 'sales', 'category' => 'Others', 'coverage' => '', 'description' => 'ON SERVICES RENDERED BY STOCK, REAL ESTATE, COMMERCIAL, CUSTOMS, INSURANCE AND IMMIGRATION BROKERS', 'tax_rate' => 12.00],
            ['tax_code' => 'VE010', 'transaction_type' => 'sales', 'category' => 'Others', 'coverage' => '', 'description' => 'WT ON VAT -EXPORTER/TRADER', 'tax_rate' => 12.00],
            ['tax_code' => 'VS111', 'transaction_type' => 'sales', 'category' => 'Others', 'coverage' => '', 'description' => 'VAT ON SERVICES RENDERED BY BANKS, NON-BANK, ETC.', 'tax_rate' => 12.00],
            
            // Purchases Data
            ['tax_code' => 'WI010', 'transaction_type' => 'purchase', 'category' => 'PROFESSIONAL (LAWYERS, CPAS, ENGINEERS, ETC.)', 'coverage' => 'If the current year\'s gross income does not exceed P3,000,000.00', 'description' => 'Individual', 'tax_rate' => 5.00],
            ['tax_code' => 'WI011', 'transaction_type' => 'purchase', 'category' => 'PROFESSIONAL (LAWYERS, CPAS, ENGINEERS, ETC.)', 'coverage' => 'If the current year\'s gross income exceed P3,000,000.00 or vat registered regardless of amount', 'description' => 'Individual', 'tax_rate' => 10.00],
            ['tax_code' => 'WI090', 'transaction_type' => 'purchase', 'category' => 'FEES OF DIRECTORS WHO ARE NOT EMPLOYEES OF THE COMPANY', 'coverage' => 'If the current year\'s gross income does not exceed P3,000,000.00', 'description' => 'Individual', 'tax_rate' => 5.00],
            ['tax_code' => 'WI091', 'transaction_type' => 'purchase', 'category' => 'FEES OF DIRECTORS WHO ARE NOT EMPLOYEES OF THE COMPANY', 'coverage' => 'If the current year\'s gross income exceeds P3,000,000.00 or vat registered regardless of amount', 'description' => 'Individual', 'tax_rate' => 10.00],
            ['tax_code' => 'WI100', 'transaction_type' => 'purchase', 'category' => 'RENTALS : real/personal properties, poles,satellites and transmission facilities, billboards', 'coverage' => '', 'description' => 'Individual', 'tax_rate' => 5.00],
            ['tax_code' => 'WI120', 'transaction_type' => 'purchase', 'category' => 'INCOME PAYMENTS TO CERTAIN CONTRACTORS', 'coverage' => '', 'description' => 'Individual', 'tax_rate' => 2.00],
            ['tax_code' => 'WI152', 'transaction_type' => 'purchase', 'category' => 'PAYMENT BY THE GENERAL PROFESSIONAL PARTNERSHIPS (GPPS) TO ITS PARTNERS', 'coverage' => 'If the current year\'s gross income does not exceed P720,000.00', 'description' => 'Individual', 'tax_rate' => 10.00],
            ['tax_code' => 'WI153', 'transaction_type' => 'purchase', 'category' => 'PAYMENT BY THE GENERAL PROFESSIONAL PARTNERSHIPS (GPPS) TO ITS PARTNERS', 'coverage' => 'If the current year\'s gross income exceeds P720,000.00', 'description' => 'Individual', 'tax_rate' => 15.00],
            ['tax_code' => 'WI158', 'transaction_type' => 'purchase', 'category' => 'INCOME PAYMENT MADE BY TOP WITHHOLDING AGENTS TO THEIR LOCAL/RESIDENT SUPPLIER OF GOODS OTHER THAN THOSE COVERED BY OTHER RATES OF WITHHOLDING TAX', 'coverage' => '', 'description' => 'Individual', 'tax_rate' => 1.00],
            ['tax_code' => 'WI160', 'transaction_type' => 'purchase', 'category' => 'INCOME PAYMENT MADE BY TOP WITHHOLDING AGENTS TO THEIR LOCAL/RESIDENT SUPPLIER OF SERVICES OTHER THAN THOSE COVERED BY OTHER RATES OF WITHHOLDING TAX', 'coverage' => '', 'description' => 'Individual', 'tax_rate' => 2.00],
            ['tax_code' => 'WC010', 'transaction_type' => 'purchase', 'category' => 'PROFESSIONAL (LAWYERS, CPAS, ENGINEERS, ETC.)', 'coverage' => '', 'description' => 'Corporation', 'tax_rate' => 10.00],
            ['tax_code' => 'WC011', 'transaction_type' => 'purchase', 'category' => 'PROFESSIONAL (LAWYERS, CPAS, ENGINEERS, ETC.)', 'coverage' => '', 'description' => 'Corporation', 'tax_rate' => 15.00],
            ['tax_code' => 'WC100', 'transaction_type' => 'purchase', 'category' => 'Rentals : real/personal properties, poles,satellites and transmission facilities, billboards', 'coverage' => '', 'description' => 'Corporation', 'tax_rate' => 5.00],
            ['tax_code' => 'WC120', 'transaction_type' => 'purchase', 'category' => 'INCOME PAYMENTS TO CERTAIN CONTRACTORS', 'coverage' => '', 'description' => 'Corporation', 'tax_rate' => 2.00],
            ['tax_code' => 'WC158', 'transaction_type' => 'purchase', 'category' => 'INCOME PAYMENT MADE BY TOP WITHHOLDING AGENTS TO THEIR LOCAL/RESIDENT SUPPLIER OF GOODS OTHER THAN THOSE COVERED BY OTHER RATES OF WITHHOLDING TAX', 'coverage' => '', 'description' => 'Corporation', 'tax_rate' => 1.00],
            ['tax_code' => 'WC160', 'transaction_type' => 'purchase', 'category' => 'INCOME PAYMENT MADE BY TOP WITHHOLDING AGENTS TO THEIR LOCAL/RESIDENT SUPPLIER OF SERVICES OTHER THAN THOSE COVERED BY OTHER RATES OF WITHHOLDING TAX', 'coverage' => '', 'description' => 'Corporation', 'tax_rate' => 2.00],
        ];

        foreach ($data as $item) {
            Atc::create($item);
        }
    }
}