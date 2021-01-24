<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
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
        // Filling the products table
        {
            DB::table('products')->delete(); // delete all rows
            $data = [
                [
                    'id'=>1,   // NOTE: settings an explicit ID value for manual linking in 'product_category' table below
                    'name' => 'Кошка пушистая',
                    'description' => 'Хорошая кошка :)',
                    'quantity' => 1,
                    'price' => 100.00,
                    'active' => true,
                    'published'=>true,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ],
                [
                    'id'=>2,
                    'name' => 'Кошка трёхцветная',
                    'description' => 'Хорошая добрая кошка',
                    'quantity' => 1,
                    'price' => 90.00,
                    'active' => true,
                    'published'=>true,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'id'=>3,
                    'name' => 'Котёнок крысолов',
                    'description' => 'Ласковый котёнок, любит охотиться',
                    'quantity' => 1,
                    'price' => 80.00,
                    'active' => true,
                    'published'=>true,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'id'=>4,
                    'name' => 'Мышка белая',
                    'description' => 'Лабораторная белая мышь',
                    'quantity' => 10,
                    'price' => 70.00,
                    'active' => true,
                    'published'=>true,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'id'=>5,
                    'name' => 'Мышка серая',
                    'description' => 'Лабораторная серая мышь',
                    'quantity' => 10,
                    'price' => 60.00,
                    'active' => true,
                    'published'=>false,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'id'=>6,
                    'name' => 'Мышь полёвка',
                    'description' => 'Дикая полевая бурая мышь',
                    'quantity' => 2,
                    'price' => 50.00,
                    'active' => true,
                    'published'=>false,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'id'=>7,
                    'name' => 'Хомяк обыкновенный',
                    'description' => 'Хомяк обыкновенный обычный',
                    'quantity' => 10,
                    'price' => 40.00,
                    'active' => false,
                    'published'=>false,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'id'=>8,
                    'name' => 'Хомяк полевой дикий',
                    'description' => 'Хомяк полевой дикий крупный',
                    'quantity' => 10,
                    'price' => 30.00,
                    'active' => false,
                    'published'=>false,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]
            ];
            DB::table('products')->insert($data);
        }
        // Filling the categories table
        {
            DB::table('categories')->delete(); // delete all rows
            $data = [
                [
                    'id'=> 1, // NOTE: settings an explicit ID value for manual linking in 'product_category' table below
                    'name' => 'Кошки',
                    'description' => 'Кошки домашние',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ],
                [
                    'id'=> 2,
                    'name' => 'Мыши',
                    'description' => 'Мыши обычные',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'id'=> 3,
                    'name' => 'Хомяки',
                    'description' => 'Хомяки разные',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'id'=> 4,
                    'name' => 'Домашние животные',
                    'description' => 'Разные домашние животные',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]
            ];
            DB::table('categories')->insert($data);
        }
        // Filling the categories table
        {
            DB::table('product_category')->delete(); // delete all rows
            $data = [
                [
                    'product_id'=> 1,
                    'category_id' => 1,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 2,
                    'category_id' => 1,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 3,
                    'category_id' => 1,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 4,
                    'category_id' => 2,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 5,
                    'category_id' => 2,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 6,
                    'category_id' => 2,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 7,
                    'category_id' => 3,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 8,
                    'category_id' => 3,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],


                [
                    'product_id'=> 1,
                    'category_id' => 4,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 2,
                    'category_id' => 4,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 3,
                    'category_id' => 4,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 4,
                    'category_id' => 4,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 5,
                    'category_id' => 4,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 6,
                    'category_id' => 4,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 7,
                    'category_id' => 4,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ],
                [
                    'product_id'=> 8,
                    'category_id' => 4,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]

            ];
            DB::table('product_category')->insert($data);
        }

    }
}
