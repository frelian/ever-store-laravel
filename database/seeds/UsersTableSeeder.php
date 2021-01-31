<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creo un usuarios para la tienda
        User::create([
            'name'          => 'Admin 1',
            'email'          => 'user1_ever_store@yopmail.com',
            'email_verified_at' => now(),
            'password'       => bcrypt('123'),
        ]);

        User::create([
            'name'          => 'Admin 2',
            'email'          => 'user2_ever_store@yopmail.com',
            'email_verified_at' => now(),
            'password'       => bcrypt('123'),
        ]);
    }
}
