<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
        // create a user
        $user = \App\Models\User::create([
            'name' => 'admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'user_type' => '3',
            'status' => '1'
        ]);
         

        // create tags
        $this->call(TagsSeeder::class);


    }
}
