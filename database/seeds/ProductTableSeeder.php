<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Product::class, 50)->create()->each(function ($product) {
            $product->status()->save(factory(\App\Model\Status::class)->make());
        });
    }
}
