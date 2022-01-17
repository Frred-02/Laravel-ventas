<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
/*importamos el modelo/ */
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        product::create([
            'name'=>'LARAVEL Y LIVEWIRE',
            'cost'=> 200,
            'price'=>350,
            'barcode'=>'00050145715',
            'stock'=> 1000,
            'alerts'=> 10,
            'category_id'=> 1,
            'image'=>'curso.png'
    
           ]);
           product::create([
            'name'=>'PC GAMERS',
            'cost'=> 1400,
            'price'=>3800,
            'barcode'=>'00050145715',
            'stock'=> 1000,
            'alerts'=> 10,
            'category_id'=> 2,
            'image'=>'curso.png'
    
           ]);
    
           product::create([
            'name'=>'TELEVISOR ',
            'cost'=> 600,
            'price'=>2000,
            'barcode'=>'00050145715',
            'stock'=> 1000,
            'alerts'=> 10,
            'category_id'=> 3,
            'image'=>'televisor.png'
    
           ]);
    
           product::create([
            'name'=>'TECLADO GAMERS',
            'cost'=> 100,
            'price'=> 350,
            'barcode'=>'00050145715',
            'stock'=> 1000,
            'alerts'=> 10,
            'category_id'=> 4,
            'image'=>'curso.png'
    
           ]);
    }
}
