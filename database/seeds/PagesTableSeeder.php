<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'name' => 'Horse',
            'user_id' => 1,
            'book_id' => 1,
            'outline_url' => 'horse.png',
            'colored_url' => 'horse-colored.png',
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
			'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
