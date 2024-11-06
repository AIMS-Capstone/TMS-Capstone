<?php

namespace App\Exports;

use App\Models\Transactions;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneralLedgerExport implements FromCollection, WithHeadings
{
    protected $year, $month, $startMonth, $endMonth, $status, $period, $quarter;

    public function __construct($year = null, $month = null, $startMonth = null, $endMonth = null, $status = 'draft', $period = 'annually', $quarter = null)
    {
        $this->year = $year ?? now()->year;
        $this->month = $month;
        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
        $this->status = $status;
        $this->period = $period;
        $this->quarter = $quarter;
    }

    public function collection()
    {
        $query = Transactions::with('taxRows.coaAccount')
        ->whereYear('date', $this->year); // Filter by year

        // Apply specific date filters based on the selected period
        if ($this->period === 'monthly' && $this->month) {
            $query->whereMonth('date', $this->month);
        } elseif ($this->period === 'quarterly' && $this->startMonth && $this->endMonth) {
            $query->whereMonth('date', '>=', $this->startMonth)
                ->whereMonth('date', '<=', $this->endMonth);
        }

        // Apply status filter if provided
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Retrieve data and format it
        $transactionsData = $query->get()->flatMap(function ($transaction) {
            return $transaction->taxRows->map(function ($taxRow) {
                return [
                    'Account Code' => $taxRow->coaAccount->code ?? 'N/A',
                    'Account' => $taxRow->coaAccount->name ?? 'N/A',
                    'Account Type' => $taxRow->coaAccount->type ?? 'N/A',
                    'Debit' => $taxRow->debit ?? 0,
                    'Credit' => $taxRow->credit ?? 0,
                ];
            });
        });
        // Add filtered date information as the first row
        $filteredDateInfo = collect([
            [
                'Account Code' => 'Filtered Date Info',
                'Date' => "Year: {$this->year}, Period: {$this->period}, Month: {$this->month}, Quarter: {$this->quarter}, Status: {$this->status}",
                'Account' => '',
                'Account Type' => '',
                'Debit' => '',
                'Credit' => '',
            ],
        ]);

        // Combine the filtered date information row with the transactions data
        return $filteredDateInfo->merge($transactionsData);

    }

    public function headings(): array
    {
        return ['Account Code', 'Account', 'Account Type', 'Debit', 'Credit'];
    }
}
