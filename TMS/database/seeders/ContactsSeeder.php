<?php

namespace Database\Seeders;

use App\Models\Contacts;
use Illuminate\Database\Seeder;

class ContactsSeeder extends Seeder
{
    public function run(): void
    {
        // Generate 10 contacts for organization_id 1
        Contacts::factory()->count(10)->create();
    }
}
