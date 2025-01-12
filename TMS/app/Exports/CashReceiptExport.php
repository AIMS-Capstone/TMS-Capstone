<?php

namespace App\Exports;

use App\Models\Transactions;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashReceiptExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
{
    protected $year, $month, $startMonth, $endMonth, $status, $period, $quarter, $contact, $organization, $user;

    public function __construct($year = null, $month = null, $startMonth = null, $endMonth = null, $status = 'draft', $period = 'annually', $quarter = null, $contact = null, $organization = null, $user = null)
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
        $this->user = $user;

    }

    public function collection()
    {
        $query = Transactions::with('contactDetails', 'taxRows.coaAccount', 'taxRows.atc', 'taxRows.taxType')
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
        $transactionsData = $query->get()->map(function ($transaction) {
            return $transaction->taxRows->map(function ($taxRow) use ($transaction) {

                $vatableAmount = (float) ($transaction->vatable_sales ?? 0);
                $taxExemptAmount = (float) ($transaction->tax_exempt_amount ?? 0);
                $zeroRatedAmount = (float) ($transaction->zero_rated_amount ?? 0);
                $taxAmount = (float) ($taxRow->tax_amount ?? 0);

                // Calculate Gross Sales for each row
                $grossAmount = $vatableAmount + $taxExemptAmount + $zeroRatedAmount + $taxAmount;

                return [
                    'Date' => $transaction->date ? Carbon::parse($transaction->date)->format('F d, Y') : 'N/A',
                    'TIN' => $transaction->contactDetails->contact_tin ?? 'N/A',
                    'Customer Name' => $transaction->contactDetails->bus_name ?? 'N/A',
                    'Customer Address' => $transaction->contactDetails->contact_address ?? 'N/A',
                    'Invoice No.' => $transaction->inv_number ?? 'N/A',
                    'Reference No.' => $transaction->reference ?? 'N/A',
                    'Description' => $taxRow->description ?? 'N/A',
                    'VATable Amount' => $vatableAmount ?: '0.00',
                    'Tax Exempt Amount' => $taxExemptAmount ?: '0.00',
                    'Zero Rated Amount' => $zeroRatedAmount ?: '0.00',
                    'Tax Amount' => $taxAmount ?: '0.00',   
                    'Gross Amount' => $grossAmount ?: '0.00',
                    'Coa Code' => $taxRow->coaAccount->code ?: '0',
                    'Coa Title' => $taxRow->coaAccount->name ?: 'N/A',
                ];
            });
        });

        $totalVATableAmount = $transactionsData->flatten(1)->sum('VATable Amount');
        $totalTaxExemptAmount = $transactionsData->flatten(1)->sum('Tax Exempt Amount');
        $totalZeroRatedAmount = $transactionsData->flatten(1)->sum('Zero Rated Amount');
        $totalTaxAmount = $transactionsData->flatten(1)->sum('Tax Amount');

        // Calculate total Gross Sales as the sum of row-wise gross sales
        $totalGrossAmount = $totalVATableAmount + $totalTaxExemptAmount + $totalZeroRatedAmount + $totalTaxAmount;

        $summaryRow = [
            [
                'Date' => 'TOTAL',
                'TIN' => '',
                'Customer Name' => '',
                'Customer Address' => '',
                'Invoice No.' => '',
                'Reference No.' => '',
                'Description' => '',
                'VATable Amount' => $totalVATableAmount,
                'Tax Exempt Amount' => $totalTaxExemptAmount,
                'Zero Rated Amount' => $totalZeroRatedAmount,
                'Tax Amount' => $totalTaxAmount,
                'Gross Amount' => $totalGrossAmount,
                'Coa Code' => '',
                'Coa Title' => '',
            ],
        ];

        return $transactionsData->flatten(1)->merge($summaryRow);

    }

    public function headings(): array
    {
        return [
            'DATE', 'TIN', 'CUSTOMER NAME', 'CUSTOMER ADDRESS', 'INVOICE NO.', 'REFERENCE NO.', 'DESCRIPTION',
            'VATABLE AMOUNT', 'VAT EXEMPT AMOUNT', 'ZERO RATED AMOUNT', 'TAX AMOUNT', 'GROSS AMOUNT', 'COA CODE', 'COA TITLE',
        ];
    }

    public function startCell(): string
    {
        return 'A10';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', 'CASH RECEIPT JOURNAL');
        $sheet->setCellValue('A2', "OWNER'S NAME: ");
        $sheet->setCellValue('B2', ($this->organization->registration_name ?? 'N/A'));

        $sheet->setCellValue('C1', $this->status ?? 'N/A');

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

        $sheet->setCellValue('L2', 'RUN DATE: ' . now()->format('m-d-Y'));
        $sheet->setCellValue('L3', 'APPLICATION SOFTWARE: Taxuri');
        $sheet->setCellValue('L4', 'PERMIT TO USE NO.:');
        $sheet->setCellValue('L5', 'VALID UNTIL:');

        $sheet->getStyle('A10:N10')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(14);

        return [
            'A1:C1' => ['font' => ['bold' => true, 'size' => 14]],
            'A10:N10' => ['font' => ['bold' => true]],
        ];
    }
}