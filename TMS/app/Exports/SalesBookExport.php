<?php

namespace App\Exports;

use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesBookExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
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
        $query = Transactions::where('transaction_type', 'Sales')
            ->where('status', $this->status)
            ->with('contactDetails', 'taxRows.coaAccount', 'taxRows.atc', 'taxRows.taxType')
            ->whereYear('date', $this->year);

        if ($this->period === 'monthly' && $this->month) {
            $query->whereMonth('date', $this->month);
        } elseif ($this->period === 'quarterly' && $this->startMonth && $this->endMonth) {
            $query->whereMonth('date', '>=', $this->startMonth)
                ->whereMonth('date', '<=', $this->endMonth);
        }

        $transactionsData = $query->get()->map(function ($transaction) {
            return $transaction->taxRows->map(function ($taxRow) use ($transaction) {
                $vatableSales = (float) ($transaction->vatable_sales ?? 0);    
                $taxExempt = (float) ($transaction->tax_exempt_amount ?? 0);
                $zeroRated = (float) ($transaction->zero_rated_amount ?? 0);
                $vatAmt = (float) ($transaction->vat_amount ?? 0);
                $deferredVatAmt = (float) ($transaction->deferred_vat_amt ?? 0);

                // Calculate Gross Sales for each row
                $grossSales = $vatableSales + $taxExempt + $zeroRated + $vatAmt + $deferredVatAmt;

                return [
                    'Date' => $transaction->date ? Carbon::parse($transaction->date)->format('F d, Y') : 'N/A',
                    'TIN' => $transaction->contactDetails->contact_tin ?? 'N/A',
                    'Customer Name' => $transaction->contactDetails->bus_name ?? 'N/A',
                    'Customer Address' => $transaction->contactDetails->contact_address ?? 'N/A',
                    'Description' => $taxRow->description ?? 'N/A',
                    'Document Type' => $transaction->doc_type ?? 'N/A',
                    'Invoice No.' => $transaction->inv_number ?? 'N/A',
                    'VATable Sales Amount' => $vatableSales ?: '0.00',
                    'Tax Exempt Amount' => $taxExempt ?: '0.00',
                    'Zero Rated Sales' => $zeroRated ?: '0.00',
                    'VAT Amt' => $vatAmt ?: '0.00',
                    'Deferred VAT Amt' => $deferredVatAmt ?: '0.00',
                    'Discount' => (float) ($transaction->discount ?? 0) ?: '0.00',
                    'Gross Sales' => $grossSales ?: '0.00',
                ];
            });
        });

        $totalVATableSales = $transactionsData->flatten(1)->sum('VATable Sales Amount');
        $totalTaxExempt = $transactionsData->flatten(1)->sum('Tax Exempt Amount');
        $totalZeroRated = $transactionsData->flatten(1)->sum('Zero Rated Sales');
        $totalVATAmt = $transactionsData->flatten(1)->sum('VAT Amt');
        $totalDeferredVATAmt = $transactionsData->flatten(1)->sum('Deferred VAT Amt');
        $totalDiscount = $transactionsData->flatten(1)->sum('Discount');

        // Calculate total Gross Sales as the sum of row-wise gross sales
        $totalGrossSales = $totalVATableSales + $totalTaxExempt + $totalZeroRated + $totalVATAmt + $totalDeferredVATAmt;

        $summaryRow = [
            [
                'Date' => 'TOTAL',
                'TIN' => '',
                'Customer Name' => '',
                'Customer Address' => '',
                'Description' => '',
                'Document Type' => '',
                'Invoice No.' => '',
                'VATable Sales Amount' => $totalVATableSales,
                'Tax Exempt Amount' => $totalTaxExempt,
                'Zero Rated Sales' => $totalZeroRated,
                'VAT Amt' => $totalVATAmt,
                'Deferred VAT Amt' => $totalDeferredVATAmt,
                'Discount' => $totalDiscount,
                'Gross Sales' => $totalGrossSales,
            ],
        ];

        return $transactionsData->flatten(1)->merge($summaryRow);
    }

    public function headings(): array
    {
        return [
            'DATE', 'TIN', 'CUSTOMER NAME', 'CUSTOMER ADDRESS', 'DESCRIPTION', 'DOCUMENT TYPE', 'INVOICE NO.',
            'VATABLE SALES AMOUNT', 'VAT EXEMPT SALES', 'ZERO RATED SALES', 'VAT AMT', 'DEFERRED VAT AMT', 'DISCOUNT', 'GROSS SALES',
        ];
    }

    public function startCell(): string
    {
        return 'A10';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', 'SALES JOURNAL');
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
