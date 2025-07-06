<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;



class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Espresso', 'price' => 2.50, 'category' => 'coffee'],
            ['name' => 'Cappuccino', 'price' => 3.00, 'category' => 'coffee'],
            ['name' => 'Freddo Espresso', 'price' => 3.20, 'category' => 'coffee'],
            ['name' => 'Freddo Cappuccino', 'price' => 3.50, 'category' => 'coffee'],
            ['name' => 'Toast Ζαμπόν-Τυρί', 'price' => 2.80, 'category' => 'snack'],
            ['name' => 'Club Sandwich', 'price' => 5.90, 'category' => 'snack'],
            ['name' => 'Amstel Beer', 'price' => 3.50, 'category' => 'drinks'],
            ['name' => 'Coca Cola 330ml', 'price' => 2.00, 'category' => 'drinks'],
        ];

        // Προσθέτουμε τυχαία 'target'
        $targets = ['kitchen', 'bar'];

        $items = collect($items)->map(function ($item) use ($targets) {
            $item['target'] = $targets[array_rand($targets)];
            return $item;
        })->toArray();
        foreach ($items as $item) {
            MenuItem::create($item);
        }
    }
}
