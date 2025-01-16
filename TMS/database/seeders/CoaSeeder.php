<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoaSeeder extends Seeder
{
    public function run()
    {

        $coaData = [
            ['type' => 'Assets', 'sub_type' => 'Non Current Assets', 'code' => '190', 'name' => 'Other Assets', 'description' => 'Other assets are a grouping of accounts that are listed as a separate line item in the assets section of the balance sheet. This line item contains minor assets that do not naturally fit into any of the main asset categories.'],
            ['type' => 'Equity', 'sub_type' => 'Equity', 'code' => '314', 'name' => 'Additional Paid-in Capital', 'description' => 'Additional Paid In Capital (APIC) is the value of share capital above its stated par value and is an accounting item under Shareholders\' Equity on the balance sheet. APIC can be created whenever a company issues new shares and can be reduced when a company repurchases its shares.'],
            ['type' => 'Assets', 'sub_type' => 'Fixed Assets', 'code' => '150', 'name' => 'Intangible Assets', 'description' => 'An intangible asset is an asset that is not physical in nature. Goodwill, brand recognition and intellectual property, such as patents, trademarks and copyrights, are all intangible assets.'],
            ['type' => 'Cost of Goods Sold', 'sub_type' => 'Cost Of Goods Sold', 'code' => '501', 'name' => 'Cost of Goods Sold', 'description' => 'Cost of goods sold (COGS) refers to the direct costs of producing the goods sold by a company. This amount includes the cost of the materials and labor directly used to create the good.'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '160', 'name' => 'Other Receivables', 'description' => 'Any other income that does not relate to normal business activities and is not recurring.'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '110', 'name' => 'Accounts Receivable', 'description' => 'Amounts owed to the business by customers for goods or services provided on credit.'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '112', 'name' => 'Prepaid Expenses', 'description' => 'Payments made in advance for goods or services to be received in the future.'],
            ['type' => 'Assets', 'sub_type' => 'Fixed Assets', 'code' => '115', 'name' => 'Office Equipment', 'description' => 'Furniture, computers, and other equipment used in business operations.'],
            ['type' => 'Liabilities', 'sub_type' => 'Current Liabilities', 'code' => '210', 'name' => 'Accounts Payable', 'description' => 'Amounts the business owes to suppliers for purchases made on credit.'],
            ['type' => 'Liabilities', 'sub_type' => 'Non Current Liabilities', 'code' => '215', 'name' => 'Deferred Tax Liability', 'description' => 'Taxes that have been accrued but not yet paid by the business.'],
            ['type' => 'Liabilities', 'sub_type' => 'Current Liabilities', 'code' => '220', 'name' => 'Accrued Expenses', 'description' => 'Expenses that have been incurred but not yet paid.'],
            ['type' => 'Liabilities', 'sub_type' => 'Non Current Liabilities', 'code' => '230', 'name' => 'Notes Payable', 'description' => 'Written promises to pay a specific amount at a future date.'],
            ['type' => 'Equity', 'sub_type' => 'Equity', 'code' => '311', 'name' => 'Common Stock', 'description' => 'Equity securities issued by the company to its shareholders.'],
            ['type' => 'Equity', 'sub_type' => 'Equity', 'code' => '312', 'name' => 'Preferred Stock', 'description' => 'Equity securities that have priority over common stock in the distribution of dividends.'],
            ['type' => 'Revenue', 'sub_type' => 'Sales', 'code' => '410', 'name' => 'Sales Revenue', 'description' => 'Revenue generated from the primary business operations.'],
            ['type' => 'Revenue', 'sub_type' => 'Other Income', 'code' => '415', 'name' => 'Interest Income', 'description' => 'Income earned from interest-bearing assets.'],
            ['type' => 'Revenue', 'sub_type' => 'Other Income', 'code' => '420', 'name' => 'Dividend Income', 'description' => 'Income received from investments in equity securities.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '510', 'name' => 'Salaries Expense', 'description' => 'Payments made to employees for their services.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '520', 'name' => 'Rent Expense', 'description' => 'Payments made for leasing office or production spaces.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '530', 'name' => 'Utilities Expense', 'description' => 'Payments made for utilities like electricity, water, and internet.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '540', 'name' => 'Office Supplies Expense', 'description' => 'Cost of office supplies consumed during the period.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '550', 'name' => 'Insurance Expense', 'description' => 'Payments made for insurance policies during the period.'],
            ['type' => 'Expenses', 'sub_type' => 'Depreciation', 'code' => '560', 'name' => 'Depreciation Expense', 'description' => 'Allocating the cost of tangible assets over their useful lives.'],
            ['type' => 'Expenses', 'sub_type' => 'Cost of Goods Sold', 'code' => '570', 'name' => 'Raw Materials Expense', 'description' => 'Cost of raw materials used in production.'],
            ['type' => 'Expenses', 'sub_type' => 'Cost of Goods Sold', 'code' => '580', 'name' => 'Direct Labor Expense', 'description' => 'Labor costs directly associated with production.'],
            ['type' => 'Expenses', 'sub_type' => 'Cost of Goods Sold', 'code' => '590', 'name' => 'Factory Overhead Expense', 'description' => 'Indirect costs related to production, such as maintenance and utilities for production facilities.'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '120', 'name' => 'Inventory', 'description' => 'Goods available for sale or used in production.'],
            ['type' => 'Assets', 'sub_type' => 'Non Current Assets', 'code' => '140', 'name' => 'Buildings', 'description' => 'Structures owned by the company used in operations.'],
            ['type' => 'Assets', 'sub_type' => 'Non Current Assets', 'code' => '145', 'name' => 'Land', 'description' => 'Real estate property owned by the company.'],
            ['type' => 'Assets', 'sub_type' => 'Non Current Assets', 'code' => '155', 'name' => 'Machinery', 'description' => 'Machines and equipment used in production or operations.'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '125', 'name' => 'Cash and Cash Equivalents', 'description' => 'Liquid assets readily available for operations.'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '130', 'name' => 'Marketable Securities', 'description' => 'Short-term investments that can be easily converted to cash.'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '135', 'name' => 'Short-term Investments', 'description' => 'Investments expected to be sold within one year.'],
            ['type' => 'Liabilities', 'sub_type' => 'Current Liabilities', 'code' => '250', 'name' => 'Wages Payable', 'description' => 'Wages owed to employees but not yet paid.'],
            ['type' => 'Liabilities', 'sub_type' => 'Current Liabilities', 'code' => '260', 'name' => 'Income Taxes Payable', 'description' => 'Taxes owed to the government but not yet paid.'],
            ['type' => 'Liabilities', 'sub_type' => 'Non Current Liabilities', 'code' => '270', 'name' => 'Bonds Payable', 'description' => 'Long-term debt issued by the company.'],
            ['type' => 'Liabilities', 'sub_type' => 'Non Current Liabilities', 'code' => '280', 'name' => 'Mortgage Payable', 'description' => 'Loan secured by real estate owned by the company.'],
            ['type' => 'Equity', 'sub_type' => 'Retained Earnings', 'code' => '320', 'name' => 'Retained Earnings', 'description' => 'Accumulated net income retained for reinvestment in the business.'],
            ['type' => 'Equity', 'sub_type' => 'Equity', 'code' => '330', 'name' => 'Treasury Stock', 'description' => 'Shares that were issued and subsequently repurchased by the company.'],
            ['type' => 'Revenue', 'sub_type' => 'Sales', 'code' => '450', 'name' => 'Service Revenue', 'description' => 'Revenue generated from providing services to customers.'],
            ['type' => 'Revenue', 'sub_type' => 'Other Income', 'code' => '460', 'name' => 'Rental Income', 'description' => 'Income earned from leasing property or equipment.'],
            ['type' => 'Revenue', 'sub_type' => 'Other Income', 'code' => '470', 'name' => 'Gain on Sale of Assets', 'description' => 'Profit earned from the sale of long-term assets.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '610', 'name' => 'Advertising Expense', 'description' => 'Costs incurred for promoting the business and its products or services.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '620', 'name' => 'Travel Expense', 'description' => 'Costs related to business travel.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '630', 'name' => 'Repairs and Maintenance Expense', 'description' => 'Costs for maintaining and repairing company assets.'],
            ['type' => 'Expenses', 'sub_type' => 'Non-operating Expenses', 'code' => '640', 'name' => 'Interest Expense', 'description' => 'Costs incurred on borrowed funds.'],
            ['type' => 'Expenses', 'sub_type' => 'Non-operating Expenses', 'code' => '650', 'name' => 'Loss on Sale of Assets', 'description' => 'Loss incurred from the sale of long-term assets.'],
            ['type' => 'Expenses', 'sub_type' => 'Non-operating Expenses', 'code' => '660', 'name' => 'Amortization Expense', 'description' => 'Allocation of the cost of intangible assets over their useful lives.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '670', 'name' => 'Bad Debt Expense', 'description' => 'Costs related to uncollectible accounts receivable.'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '140', 'name' => 'Deposits', 'description' => 'Funds held in accounts or as security for agreements.'],
            ['type' => 'Assets', 'sub_type' => 'Fixed Assets', 'code' => '180', 'name' => 'Vehicles', 'description' => 'Company-owned vehicles used for business operations.'],
            ['type' => 'Liabilities', 'sub_type' => 'Current Liabilities', 'code' => '290', 'name' => 'Unearned Revenue', 'description' => 'Revenue received in advance for goods or services to be provided in the future.'],
            ['type' => 'Liabilities', 'sub_type' => 'Non Current Liabilities', 'code' => '295', 'name' => 'Long-term Debt', 'description' => 'Debt obligations that are due beyond one year.'],
            ['type' => 'Equity', 'sub_type' => 'Equity', 'code' => '340', 'name' => 'Contributed Capital', 'description' => 'Capital contributed by the company’s owners or shareholders.'],
            ['type' => 'Revenue', 'sub_type' => 'Sales', 'code' => '480', 'name' => 'Product Revenue', 'description' => 'Revenue generated from the sale of goods.'],
            ['type' => 'Revenue', 'sub_type' => 'Other Income', 'code' => '490', 'name' => 'Miscellaneous Income', 'description' => 'Income earned from non-primary business activities.'],
            ['type' => 'Expenses', 'sub_type' => 'Cost of Goods Sold', 'code' => '710', 'name' => 'Production Supplies Expense', 'description' => 'Cost of supplies used in the production process.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '720', 'name' => 'Training Expense', 'description' => 'Costs related to employee training and development.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '730', 'name' => 'Licensing Expense', 'description' => 'Fees paid for licenses or permits required for operations.'],
            ['type' => 'Expenses', 'sub_type' => 'Non-operating Expenses', 'code' => '740', 'name' => 'Legal Fees', 'description' => 'Costs incurred for legal services.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '750', 'name' => 'Audit Fees', 'description' => 'Costs incurred for auditing services.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '760', 'name' => 'Consulting Fees', 'description' => 'Expenses paid for professional consulting services.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '770', 'name' => 'IT Services Expense', 'description' => 'Costs incurred for IT services and software subscriptions.'],
            ['type' => 'Assets', 'sub_type' => 'Non Current Assets', 'code' => '185', 'name' => 'Patents', 'description' => 'Intellectual property rights owned by the company.'],
            ['type' => 'Assets', 'sub_type' => 'Non Current Assets', 'code' => '190', 'name' => 'Trademarks', 'description' => 'Trademark rights owned by the company.'],
            ['type' => 'Liabilities', 'sub_type' => 'Current Liabilities', 'code' => '300', 'name' => 'Short-term Loans', 'description' => 'Loans due within one year.'],
            ['type' => 'Liabilities', 'sub_type' => 'Non Current Liabilities', 'code' => '310', 'name' => 'Capital Lease Obligation', 'description' => 'Long-term obligations under capital leases.'],
            ['type' => 'Equity', 'sub_type' => 'Equity', 'code' => '350', 'name' => 'Accumulated Other Comprehensive Income', 'description' => 'Cumulative changes in equity not included in net income.'],
            ['type' => 'Revenue', 'sub_type' => 'Other Income', 'code' => '500', 'name' => 'Grants and Subsidies', 'description' => 'Income received from government or other entities as grants or subsidies.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '800', 'name' => 'Research and Development Expense', 'description' => 'Costs incurred for research and development activities.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '810', 'name' => 'Employee Benefits Expense', 'description' => 'Costs incurred for employee benefits such as health insurance and retirement contributions.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '820', 'name' => 'Subscription Fees', 'description' => 'Costs for subscriptions to professional tools or services.'],
            ['type' => 'Assets', 'sub_type' => 'Fixed Assets', 'code' => '195', 'name' => 'Leasehold Improvements', 'description' => 'Improvements made to leased property.'],
            ['type' => 'Liabilities', 'sub_type' => 'Current Liabilities', 'code' => '320', 'name' => 'Dividends Payable', 'description' => 'Dividends declared but not yet paid to shareholders.'],
            ['type' => 'Liabilities', 'sub_type' => 'Non Current Liabilities', 'code' => '330', 'name' => 'Deferred Revenue', 'description' => 'Revenue received for goods or services to be provided in future periods.'],
            ['type' => 'Equity', 'sub_type' => 'Equity', 'code' => '360', 'name' => 'Reserves', 'description' => 'Portions of equity set aside for specific purposes.'],
            ['type' => 'Revenue', 'sub_type' => 'Sales', 'code' => '520', 'name' => 'Export Sales Revenue', 'description' => 'Revenue generated from sales made to foreign customers.'],
            ['type' => 'Revenue', 'sub_type' => 'Other Income', 'code' => '530', 'name' => 'Foreign Exchange Gain', 'description' => 'Gains from foreign currency transactions.'],
            ['type' => 'Expenses', 'sub_type' => 'Non-operating Expenses', 'code' => '850', 'name' => 'Foreign Exchange Loss', 'description' => 'Losses from foreign currency transactions.'],
            ['type' => 'Expenses', 'sub_type' => 'Non-operating Expenses', 'code' => '860', 'name' => 'Charitable Contributions', 'description' => 'Donations made to charitable organizations.'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '870', 'name' => 'Training and Development', 'description' => 'Costs incurred for staff training and professional development programs.'],
        ];

        $now = now();

        $coaDataWithNullOrgId = array_map(function ($coa) use ($now) {
            return array_merge($coa, [
                'organization_id' => null,
                'status' => 'Active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $coaData);

        DB::table('coas')->insert($coaDataWithNullOrgId);

        $this->command->info('Chart of accounts seeded with null organization IDs.');

    }
}
