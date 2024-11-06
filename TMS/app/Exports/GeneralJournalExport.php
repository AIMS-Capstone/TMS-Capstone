<?php

namespace App\Exports;

use App\Models\Transactions;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneralJournalExport implements FromCollection, WithHeadings
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
        $query = Transactions::with('contactDetails')
            ->whereYear('date', $this->year) // Filter by year
            ->where('transaction_type', 'Journal');

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
        $transactionsData = $query->get()->map(function ($transaction) {
            return [
                'Contact' => $transaction->contactDetails->bus_name ?? 'N/A' . "\n" .
                $transaction->contactDetails->contact_address ?? 'N/A' . "\n" .
                $transaction->contactDetails->contact_tin ?? 'N/A',
                'Date' => $transaction->date ? Carbon::parse($transaction->date)->format('F d, Y') : 'N/A',
                'Invoice' => $transaction->inv_number ?? 'N/A',
                'Reference' => $transaction->reference ?? 'N/A',
                'Description' => $transaction->description ?? 'N/A',
                'Debit' => $transaction->debit ?? '0.00',
                'Credit' => $transaction->credit ?? '0.00',
            ];
        });

        // Add filtered date information as the first row
        $filteredDateInfo = collect([
            [
                'Contact' => 'Filtered Date Info',
                'Date' => "Year: {$this->year}, Period: {$this->period}, Month: {$this->month}, Quarter: {$this->quarter}",
                'Invoice' => '',
                'Reference' => '',
                'Description' => '',
                'Debit' => '',
                'Credit' => '',
            ],
        ]);

        // Combine the filtered date information row with the transactions data
        return $filteredDateInfo->merge($transactionsData);

    }

    public function headings(): array
    {
        return [
            'Contact',
            'Date',
            'Invoice',
            'Reference',
            'Description',
            'Debit',
            'Credit',
        ];
    }
}
