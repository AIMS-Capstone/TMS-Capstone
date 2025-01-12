<?php

namespace Database\Seeders;

use App\Models\Contacts;
use Illuminate\Database\Seeder;

class ContactsSeeder extends Seeder
{
    public function run(): void
    {
        // Use the factory to generate 100 contacts for organization_id 1
        Contacts::factory()->count(50)->create();
    }
}
