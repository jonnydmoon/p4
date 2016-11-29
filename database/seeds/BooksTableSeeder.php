<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('books')->insert([
			'name' => 'Animals',
			'created_at' => Carbon\Carbon::now()->toDateTimeString(),
			'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
			'user_id' => 1,
			'is_public' => 1,
		]);

		DB::table('books')->insert([
			'name' => 'Cars',
			'created_at' => Carbon\Carbon::now()->toDateTimeString(),
			'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
			'user_id' => 1,
			'is_public' => 1,
		]);

		DB::table('books')->insert([
			'name' => 'Places',
			'created_at' => Carbon\Carbon::now()->toDateTimeString(),
			'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
			'user_id' => 1,
			'is_public' => 1,
		]);

		DB::table('books')->insert([
			'name' => 'Patterns',
			'created_at' => Carbon\Carbon::now()->toDateTimeString(),
			'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
			'user_id' => 1,
			'is_public' => 1,
		]);

		DB::table('books')->insert([
			'name' => 'Flowers',
			'created_at' => Carbon\Carbon::now()->toDateTimeString(),
			'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
			'user_id' => 1,
			'is_public' => 1,
		]);





	}
}
