<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    protected static string $hashedPassword;
    public function run(): void
    {
        // User::factory(10)->create();
        static::$hashedPassword ??= Hash::make('@TaxuriTMS01');
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'suffix' => 'I',
            'email' => 'admin@gmail.com',
            'role' => 'Admin',
            'email_verified_at' => now(),
            'password' => static::$hashedPassword,
        ]);
        $this->call([
            TaxTypeSeeder::class,
            AtcSeeder::class,
            RdoSeeder::class,
            OrgsetupSeeder::class,
            ContactsSeeder::class,
            CoaSeeder::class,
            AddressSeeder::class,
            EmployeeSeeder::class,
            EmploymentSeeder::class,
            WithHoldingSeeder::class
        ]);

    }
}
