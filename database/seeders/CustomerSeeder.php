<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::insert([
            ['name' => 'John Doe', 'address' => '123 Main Street'],
            ['name' => 'Jane Smith', 'address' => '456 Oak Avenue'],
            ['name' => 'Alice Johnson', 'address' => '789 Pine Road'],
        ]);
    }
}
