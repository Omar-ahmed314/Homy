<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 10; $i++) {
            Product::create([
                'name' => $faker->words(3, true),
                'description' => $faker->sentence(10),
                'price' => $faker->randomFloat(2, 10, 500),
                'stock' => $faker->numberBetween(1, 100),
            ]);
        }
    }
}
