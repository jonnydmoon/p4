<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('helloworld'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jill',
            'email' => 'jill@harvard.edu',
            'password' => bcrypt('helloworld'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jamal',
            'email' => 'jamal@harvard.edu',
            'password' => bcrypt('helloworld'),
        ]);
    }
}