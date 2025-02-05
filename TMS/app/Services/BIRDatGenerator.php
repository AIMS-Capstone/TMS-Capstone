<?php

namespace App\Services;

use App\Models\OrgSetup;
use App\Models\TaxRow;
use Illuminate\Support\Carbon;

class BIRDatGenerator 
{
    /**
     * Generate DAT filename based on tax information
     *
     * @param string $tin Taxpayer Identification Number
     * @param string $type Return type
     * @param string|int $quarter Quarter number
     * @param string|int $year Year
     * @return string
     */
    public function generateDatFileName($tin, $type, $quarter, $year) 
    {
        $tin = preg_replace('/[^0-9]/', '', $tin);
        $type = $type === 'SAWT' ? 'SAWT' : 'SLSP';
        $quarter = str_pad(ltrim($quarter, 'Q'), 2, '0', STR_PAD_LEFT);
        $year = str_pad($year, 4, '0', STR_PAD_LEFT);
        
        return "{$tin}{$type}{$quarter}{$year}.dat";
    }

    /**
     * Generate DAT file content from transactions
     *
     * @param array $transactions Array of transaction records
     * @return string
     */
    public function generateDatContent($transactions) 
    {
        $content = '';
        
        foreach ($transactions as $record) {
            $line = $this->formatLine($record);
            $content .= $line . "\n";
        }
        
        return $content;
    }
    private function formatLine($record)
    {
        // Start with record type and transaction type
        $line = $record['record_type'] . ',';
        $line .= substr($record['transaction_type'], 0, 1) . ',';
        
        // TIN (remove hypens and quote)
        $cleanTin = str_replace('-', '', $record['tin']);
        $line .= '"' . $cleanTin . '",';
        
        // Business Name (quoted and uppercase)
        $line .= '"' . strtoupper($record['business_name']) . '",';
        
        // First, Middle, Last Name (empty or quoted)
        $line .= $this->quoteOrEmptyField($record['first_name']) . ',';
        $line .= $this->quoteOrEmptyField($record['middle_name']) . ',';
        $line .= $this->quoteOrEmptyField($record['last_name']) . ',';
        
        // Different logic for header and detail records
        if ($record['record_type'] === 'H') {
            $line .= $this->generateHeaderRecord($record);
        } else {
            $line .= $this->generateDetailRecord($record);
        }
        
        return $line;
    }
    /**
     * Generate header record content
     *
     * @param array $record Header record data
     * @return string
     */
    private function generateHeaderRecord($record)
    {
        $line = '';
        
        $line .= '"' . strtoupper($record['business_name']) . '",';
        $line .= '"' . strtoupper($record['address']) . '",';
        $line .= '"' . strtoupper($record['city']) . '",';
        
        // Amounts
        $line .= $this->formatAmount($record['zero_rated']) . ',';
        $line .= $this->formatAmount($record['exempt']) . ',';
        $line .= $this->formatAmount($record['taxable']) . ',';
        $line .= $this->formatAmount($record['atc']) . ',';
        $line .= $this->formatAmount($record['gross_amount']) . ',';
        $line .= $this->formatAmount($record['tax_withheld']) . ',';
        
        // Return Period
        $line .= '"' . ($record['return_period'] ?? '047') . '",';
        
        // Transaction Date
        $line .= '"' . $record['transaction_date'] . '",';
        
        // Record Count
        $line .= ($record['record_count'] ?? '1');
        
        return $line;
    }

    /**
     * Generate detail record content
     *
     * @param array $record Detail record data
     * @return string
     */
    private function generateDetailRecord($record)
    {
        $line = '';
        
        // Empty fields for first, middle, last names
        $line .= ',,,';
        
        // Address details (optional)
        $line .= $this->quoteOrEmptyField(strtoupper($record['address'])) . ',';
        $line .= $this->quoteOrEmptyField(strtoupper($record['city'])) . ',';
        
        // Amounts
        $line .= '0,';
        $line .= '0,';
        $line .= $this->formatAmount($record['taxable']) . ',';
        $line .= $this->formatAmount($record['tax_withheld']) . ',';
        
        // Payor TIN
        $line .= '"' . $record['payor_tin'] . '",';
        
        // Transaction Date
        $line .= '"' . $record['transaction_date'] . '"';
        
        return $line;
    }

    /**
     * Format amount to 16 chars, right-aligned with zero-fill
     *
     * @param float $amount
     * @return string
     */
    private function formatAmount($amount)
    {
        // Convert to numeric value, defaulting to 0 if not numeric
        $numericAmount = is_numeric($amount) ? floatval($amount) : 0.00;
        
        // Format with 2 decimal places, removing unnecessary trailing zeros
        return number_format($numericAmount, 2, '.', '');
    }

    private function quoteOrEmptyField($value)
    {
        return $value ? '"' . $value . '"' : '';
    }
    private function mapQuarterToMonth($quarter)
{
    switch ($quarter) {
        case 'Q1': return '1'; // January
        case 'Q2': return '4'; // April
        case 'Q3': return '7'; // July
        case 'Q4': return '10'; // October
        default: return '1'; // Default to Q1 if unexpected value
    }
}
    public function generate1702QSawtDatFile($taxReturn)
{
    // Prepare transactions array
    $transactions = [];
    
    // Fetch organization details
    $organizationId = session('organization_id');
    $organization = OrgSetup::with("rdo")
        ->where('id', $organizationId)
        ->first();
    
    // Get the transaction IDs linked to this tax return
    $transactionIds = $taxReturn->individualTransaction()->pluck('transaction_id');
    
    // Query tax rows for purchases with non-zero ATC amount
    $taxRows = TaxRow::whereHas('transaction', function ($query) use ($transactionIds) {
        $query->whereIn('transaction_type', ['sales', 'purchase'])
              ->whereIn('id', $transactionIds);
    })
    ->whereHas('taxType', function ($q) {
        $q->whereNotIn('tax_type', ['Capital Goods', 'Importation of Goods']);
    })
    ->whereNotNull('atc_amount')
    ->where('atc_amount', '>', 0)
    ->with(['transaction.contactDetails', 'taxType', 'atc'])
    ->get();
    
    // Validate tax rows
    if ($taxRows->isEmpty()) {
        throw new \Exception("No withholding tax rows found for the given return");
    }
    
    // Prepare header record (HSAWT)
    $headerRecord = [
        'record_type' => 'HSAWT',
        'transaction_type' => 'H1701Q',
        'tin' => $organization->tin ?? '',
        'business_name' => $organization->registration_name ?? '', 
        'return_period' =>  sprintf('%s/%s', 
    $this->mapQuarterToMonth($taxReturn->month), 
    $taxReturn->year),
    ];
    $transactions[] = $headerRecord;
    
    // Prepare detail records (DSAWT)
    $sequenceNumber = 1;
    foreach ($taxRows as $row) {
        $detailRecord = [
            'record_type' => 'DSAWT',
            'transaction_type' => 'D1701Q',
            'sequence_number' => $sequenceNumber++,
            'tin' => $row->transaction->contactDetails->contact_tin ?? '',
            'business_name' => $row->transaction->contactDetails->bus_name ?? '',
            'return_period' =>  sprintf('%s/%s', 
            $this->mapQuarterToMonth($taxReturn->month), 
            $taxReturn->year),
            'tax_code' => $row->atc->tax_code, // Adjust as needed
            'tax_rate' => $row->atc->tax_rate, // Standard rate, adjust if different
            'base_amount' => $row->net_amount ?? 0,
            'tax_withheld' => $row->atc_amount ?? 0,
        ];
        
        $transactions[] = $detailRecord;
    }
    
    // Prepare control record (CSAWT)
    $controlRecord = [
        'record_type' => 'CSAWT',
        'transaction_type' => 'C1701Q',
        'tin' => $organization->tin ?? '',
        'return_period' =>  sprintf('%s/%s', 
            $this->mapQuarterToMonth($taxReturn->month), 
            $taxReturn->year),
        'total_base_amount' => $taxRows->sum('net_amount') ?? 0,
        'total_tax_withheld' => $taxRows->sum('atc_amount') ?? 0,
    ];
    $transactions[] = $controlRecord;
    
    // Generate filename
    $fileName = $this->generateDatFileName(
        $organization->tin ?? '',
        'SAWT',
        $taxReturn->month,
        $taxReturn->year
    );
    
    // Generate content with custom formatting for SAWT
    $content = $this->generateSawtDatContent($transactions);
    
    // Return file for download
    return response($content)
        ->header('Content-Type', 'text/plain')
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
}

// Custom content generation for SAWT format
private function generateSawtDatContent($transactions)
{
    $content = '';
    
    foreach ($transactions as $record) {
        switch ($record['record_type']) {
            case 'HSAWT':
                $content .= sprintf(
                    "%s,%s,%s,0000,\"%s\",,,,01/2024,047\n",
                    $record['record_type'],
                    $record['transaction_type'],
                    $record['tin'],
                    $record['business_name']
                );
                break;
            
            case 'DSAWT':
                $content .= sprintf(
                    "%s,%s,%d,%s,0000,\"%s\",,,,01/2024,,WC050,%.2f,%.2f,%.2f\n",
                    $record['record_type'],
                    $record['transaction_type'],
                    $record['sequence_number'],
                    $record['tin'],
                    $record['business_name'],
                    $record['tax_rate'],
                    $record['base_amount'],
                    $record['tax_withheld']
                );
                break;
            
            case 'CSAWT':
                $content .= sprintf(
                    "%s,%s,%s,0000,01/2024,%.2f,%.2f\n",
                    $record['record_type'],
                    $record['transaction_type'],
                    $record['tin'],
                    $record['total_base_amount'],
                    $record['total_tax_withheld']
                );
                break;
        }
    }
    
    return $content;
}
}