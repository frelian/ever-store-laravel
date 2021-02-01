<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_name'  => $faker->word,
        'product_price' => $faker->numberBetween(2, 80),
        'product_info'  => $faker->sentence,
        'product_state' => (int) $faker->boolean,
    ];
});
