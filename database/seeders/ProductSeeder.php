<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Product::insert([
            ['name' => 'Pen', 'price' => 10],
            ['name' => 'Notebook', 'price' => 30],
            ['name' => 'Eraser', 'price' => 5],
        ]);
    }
    
}
