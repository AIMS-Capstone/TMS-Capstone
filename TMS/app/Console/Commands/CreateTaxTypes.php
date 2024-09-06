<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaxType;

class CreateTaxTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-tax-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        TaxType::factory()->vatOnSalesGoods()->create();
        TaxType::factory()->salesToGovernmentGoods()->create();
        TaxType::factory()->zeroRatedSalesGoods()->create();
        TaxType::factory()->taxExemptSalesGoods()->create();
        TaxType::factory()->vatOnSalesServices()->create();
        TaxType::factory()->salesToGovernmentServices()->create();
        TaxType::factory()->zeroRatedSalesServices()->create();
        TaxType::factory()->taxExemptSalesServices()->create();
        TaxType::factory()->nonTax()->create();

        $this->info('All tax types have been created.');
    }
}
