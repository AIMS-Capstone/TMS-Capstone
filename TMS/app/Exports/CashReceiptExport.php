<?php

namespace App\Exports;

use App\Models\Transactions;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CashReceiptExport implements FromCollection, WithHeadings
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
            ->where('status', 'draft')
            ->where('Paidstatus', 'Paid')
            ->where('transaction_type', 'Sales')
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

        // Map each transaction to match the headings structure
        $transactionsData =  $query->get()->map(function ($transaction) {
            return [
                'Contact' =>
                ($transaction->contactDetails->bus_name ?? 'N/A') . "\n" .
                ($transaction->contactDetails->contact_address ?? 'N/A') . "\n" .
                ($transaction->contactDetails->contact_tin ?? 'N/A'),
                'Date' => $transaction->date ? Carbon::parse($transaction->date)->format('F d, Y') : 'N/A',
                'Invoice' => $transaction->inv_number ?? 'N/A',
                'Reference' => $transaction->reference ?? 'N/A',
                'Description' => $transaction->description ?? 'N/A',
                'VATable Amount' => $transaction->vat_amount ?? '0.00',
                'Tax Exempt Amount' => $transaction->tax_exempt_amount ?? '0.00',
                'Zero Rated Amount' => $transaction->zero_rated_amount ?? '0.00',
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
                'VATable Amount' => '',
                'Tax Exempt Amount' => '',
                'Zero Rated Amount' => '',
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
            'VATable Amount',
            'Tax Exempt Amount',
            'Zero Rated Amount',
        ];
    }
}