<?php

namespace App\Exports;

use App\Models\Transactions;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseBookExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
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
        $query = Transactions::where('status', 'draft')
            ->where('transaction_type', 'Purchase')
            ->with('contactDetails', 'taxRows.coaAccount', 'taxRows.atc', 'taxRows.taxType')
            ->whereYear('date', $this->year);

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

        $transactionsData = $query->get()->map(function ($transaction) {
            return $transaction->taxRows->map(function ($taxRow) use ($transaction) {
                $vatablePurchase = (float) ($transaction->vatable_purchase ?? 0);
                $taxExempt = (float) ($transaction->tax_exempt_amount ?? 0);
                $zeroRated = (float) ($transaction->zero_rated_amount ?? 0);
                $vatAmt = (float) ($transaction->vat_amount ?? 0);
                $deferredVatAmt = (float) ($transaction->deferred_vat_amt ?? 0);

                $grossPurchase = $vatablePurchase + $taxExempt + $zeroRated + $vatAmt + $deferredVatAmt;

                return [
                    ' Date' => $transaction->date ? Carbon::parse($transaction->date)->format('F d, Y') : 'N/A',
                    'TIN' => $transaction->contactDetails->contact_tin ?? 'N/A',
                    'Customer Name' => $transaction->contactDetails->bus_name ?? 'N/A',
                    'Customer Address' => $transaction->contactDetails->contact_address ?? 'N/A',
                    'Description' => $taxRow->description ?? 'N/A',
                    'Document Type' => $transaction->doc_type ?? 'N/A',
                    'Reference' => $transaction->reference ?? 'N/A',
                    'VATable Purchase Amount' => $vatablePurchase ?: '0.00',
                    'Tax Exempt Amount' => $taxExempt ?: '0.00',
                    'Zero Rated Purchase' => $zeroRated ?: '0.00',
                    'VAT Amt' => $vatAmt ?: '0.00',
                    'Deferred VAT Amt' => $deferredVatAmt ?: '0.00',
                    'Discount' => (float) ($transaction->discount ?? 0) ?: '0.00',
                    'Gross Purchase' => $grossPurchase ?: '0.00',
                ];
            });
        });

        $totalVATablePurchase = $transactionsData->flatten(1)->sum('VATable Purchase Amount');
        $totalTaxExempt = $transactionsData->flatten(1)->sum('Tax Exempt Amount');
        $totalZeroRated = $transactionsData->flatten(1)->sum('Zero Rated Purchase');
        $totalVATAmt = $transactionsData->flatten(1)->sum('VAT Amt');
        $totalDeferredVATAmt = $transactionsData->flatten(1)->sum('Deferred VAT Amt');
        $totalDiscount = $transactionsData->flatten(1)->sum('Discount');

        // Calculate total Gross Purchase as the sum of row-wise gross Purchase
        $totalGrossPurchase = $totalVATablePurchase + $totalTaxExempt + $totalZeroRated + $totalVATAmt + $totalDeferredVATAmt;

        $summaryRow = [
            [
                'Date' => 'TOTAL',
                'TIN' => '',
                'Customer Name' => '',
                'Customer Address' => '',
                'Description' => '',
                'Document Type' => '',
                'Reference No.' => '',
                'VATable Purchase Amount' => $totalVATablePurchase,
                'Tax Exempt Amount' => $totalTaxExempt,
                'Zero Rated Purchase' => $totalZeroRated,
                'VAT Amt' => $totalVATAmt,
                'Deferred VAT Amt' => $totalDeferredVATAmt,
                'Discount' => $totalDiscount,
                'Gross Purchase' => $totalGrossPurchase,
            ],
        ];

        return $transactionsData->flatten(1)->merge($summaryRow);
    }


    public function headings(): array
    {
        return [
            'DATE', 'TIN', 'CUSTOMER NAME', 'CUSTOMER ADDRESS', 'DESCRIPTION', 'DOCUMENT TYPE', 'REFERENCE NO.',
            'VATABLE Purchase AMOUNT', 'VAT EXEMPT Purchase', 'ZERO RATED Purchase', 'VAT AMT', 'DEFERRED VAT AMT', 'DISCOUNT', 'GROSS Purchase',
        ];
    }

    public function startCell(): string
    {
        return 'A10';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', 'PURCHASE JOURNAL');
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

