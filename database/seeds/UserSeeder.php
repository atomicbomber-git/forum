<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Mr. Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin')
        ]);
    }
}
