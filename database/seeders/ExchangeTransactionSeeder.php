<?php

namespace Database\Seeders;

use App\Models\ExchangeTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExchangeTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExchangeTransaction::factory()->count(10000)->create();
    }
}
