<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
   public function run()
{
    foreach (['A', 'B', 'C'] as $zone) {
        for ($i = 1; $i <= 5; $i++) {
            Table::create([
                'number' => (string) $i,
                'zone' => $zone,
                'status' => 'free',
            ]);
        }
    }
}

}
