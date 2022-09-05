<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        
        User::truncate();
        Store::truncate();
        Product::truncate();
        ProductReview::truncate();
        
        
        $UserQuantity = 50;
        $StoreQuantity = 500;
        $ProductQuantity = 115;
        $ProductReviewQuantity = 150;
        
        User::factory($UserQuantity)->create();
        
        Store::factory($StoreQuantity)->create();
        Product::factory($ProductQuantity)->create();
        ProductReview::factory($ProductReviewQuantity)->create();
            
    }

}
