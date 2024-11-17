<?php

namespace App\Exports;

use App\Models\Transactions;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralLedgerExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
{
    protected $year, $month, $startMonth, $endMonth, $status, $period, $quarter, $contact, $organization;

    public function __construct($year = null, $month = null, $startMonth = null, $endMonth = null, $status = 'draft', $period = 'annually', $quarter = null, $contact = null, $organization = null)
    {
        $this->year = $year ?? now()->year;
        $this->month = $month;
        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
        $this->status = $status;
        $this->period = $period;
        $this->quarter = $quarter;
        $this->contact = $contact;
        $this->organization = $organization;
    }

    public function collection()
    {
        $query = Transactions::with(['taxRows.coaAccount', 'taxRows.atc', 'taxRows.taxType'])
            ->whereYear('date', $this->year);

        // Apply date and status filters as needed
        if ($this->period === 'monthly' && $this->month) {
            $query->whereMonth('date', $this->month);
        } elseif ($this->period === 'quarterly' && $this->startMonth && $this->endMonth) {
            $query->whereMonth('date', '>=', $this->startMonth)
                ->whereMonth('date', '<=', $this->endMonth);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Retrieve and format data
        $transactionsData = $query->get()->flatMap(function ($transaction) {
            return $transaction->taxRows->map(function ($taxRow) use ($transaction) {
                return [
                    'GL Date' => $transaction->date,
                    'Reference' => $transaction->reference,
                    'Description' => $taxRow->description ?? 'N/A',
                    'Account Title' => $taxRow->coaAccount->name ?? 'N/A',
                    'Debits' => $taxRow->debit ?? 0,
                    'Credits' => $taxRow->credit ?? 0,
                    'Balance' => null, // You can calculate balance if needed
                ];
            });
        });

        // Calculate total credits and debits
        $totalDebits = $transactionsData->sum('Debits');
        $totalCredits = $transactionsData->sum('Credits');

        // Append a summary row at the end
        $summaryRow = [
            [
                'GL Date' => '',
                'Reference' => '',
                'Description' => 'Total',
                'Account Title' => '',
                'Debits' => $totalDebits,
                'Credits' => $totalCredits,
                'Balance' => null,
            ],
        ];

        return collect($transactionsData)->merge($summaryRow);
    }

    public function headings(): array
    {
        return ['GL DATE', 'REFERENCE', 'BRIEF DESCRIPTION/EXPLANATION', 'ACCOUNT TITLE', 'DEBITS', 'CREDITS', 'BALANCE'];
    }

    public function startCell(): string
    {
        return 'A8'; // Start data from row 8 to leave space for the header
    }

    public function styles(Worksheet $sheet)
    {

            $sheet->setCellValue('A1', 'GENERAL LEDGER');
            $sheet->setCellValue('A2', "OWNER'S NAME: ");
            $sheet->setCellValue('B2', ($this->organization->registration_name ?? 'N/A'));

            $addressParts = [
                $this->organization->region ?? '',
                $this->organization->province ?? '',
                $this->organization->city ?? '',
                $this->organization->zip_code ?? '',
            ];
            $trimmedAddress = implode(', ', array_filter(array_map('trim', $addressParts)));
            $sheet->setCellValue('A3', "OWNER'S ADDRESS: ");
            $sheet->setCellValue('B3', ($trimmedAddress ?: 'N/A'));

            $sheet->setCellValue('A4', "VAT Reg. TIN: ");
            $sheet->setCellValue('B4', ($this->organization->tin ?? 'N/A'));
            $sheet->setCellValue('A5', "PERIOD: ");
            $sheet->setCellValue('B5', ($this->month ? $this->month : $this->year));

            $sheet->setCellValue('E2', 'RUN DATE: ' . now()->format('m-d-Y'));
            $sheet->setCellValue('E3', 'APPLICATION SOFTWARE: Taxuri');
            $sheet->setCellValue('E4', 'PERMIT TO USE NO.:');
            $sheet->setCellValue('E5', 'VALID UNTIL:');


        // Style the headings
        $sheet->getStyle('A8:G8')->getFont()->setBold(true);

        // Style the title
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        return [
            'A1:G1' => ['font' => ['bold' => true, 'size' => 14]],
            'A8:G8' => ['font' => ['bold' => true]],
        ];
    }
}
