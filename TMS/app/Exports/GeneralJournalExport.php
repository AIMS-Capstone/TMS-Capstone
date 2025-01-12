<?php

namespace App\Exports;

use App\Models\Transactions;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class GeneralJournalExport implements FromCollection, WithHeadings, WithEvents
{
    protected $year, $month, $startMonth, $endMonth, $status, $period, $quarter, $organization, $contact;

    public function __construct($year = null, $month = null, $startMonth = null, $endMonth = null, $status = null, $period = 'annually', $quarter = null, $organization = null, $contact = null)
    {
        $this->year = $year ?? now()->year;
        $this->month = $month;
        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
        $this->status = $status;
        $this->period = $period;
        $this->quarter = $quarter;
        $this->organization = $organization;
        $this->contact = $contact;
    }

    public function collection()
    {
        // Query to fetch transactions with eager-loaded relationships
        $query = Transactions::with('taxRows.coaAccount')
            ->whereYear('date', $this->year)
            ->where('transaction_type', 'Journal')
            ->where('organization_id', $this->organization->id);

        // Apply filters based on the period
        if ($this->period === 'monthly' && $this->month) {
            $query->whereMonth('date', $this->month);
        } elseif ($this->period === 'quarterly' && $this->startMonth && $this->endMonth) {
            $query->whereMonth('date', '>=', $this->startMonth)
                ->whereMonth('date', '<=', $this->endMonth);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Transform data
        $transactionsData = $query->get()->flatMap(function ($transaction) {
            if (!$transaction->taxRows->isEmpty()) {
                return $transaction->taxRows->map(function ($taxRow) use ($transaction) {
                    return [
                        'GL Date' => $transaction->date ? Carbon::parse($transaction->date)->format('n/j/Y') : '',
                        'Reference' => $transaction->reference ?? '',
                        'Account' => optional($taxRow->coaAccount)->name ?? 'N/A',
                        'Debit' => $taxRow->debit ?? '0.00',
                        'Credit' => $taxRow->credit ?? '0.00',
                    ];
                });
            }
            return [];
        });

        return $transactionsData->isNotEmpty() ? $transactionsData : collect([[
            'GL Date' => 'No data found for the selected filters',
            'Reference' => '',
            'Account' => '',
            'Debit' => '',
            'Credit' => '',
        ]]);
    }

    public function headings(): array
    {
        return [
            [($this->organization->registration_name ?? 'Organization')], // Title
            ['General Journal'], // Subtitle
            ["As of " . Carbon::now()->format('F d, Y')], // Date
            [], // Blank row
            ['GL Date', 'Reference', 'Account', 'Debit', 'Credit'], // Column headers
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge title rows
                $sheet->mergeCells('A1:E1'); // Title
                $sheet->mergeCells('A2:E2'); // Subtitle
                $sheet->mergeCells('A3:E3'); // Date

                // Apply styles to title and header
                $sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                ]);
                $sheet->getStyle('A2:E2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => 'center'],
                ]);
                $sheet->getStyle('A3:E3')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 10],
                    'alignment' => ['horizontal' => 'center'],
                ]);
            },
        ];
    }
}
