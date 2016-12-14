<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//
		DB::table('student')->insert(
			[
				['name' => 'syc', 'age' => 10, 'sex' => 22, 'ctime' => time(), 'utime' => time()],
				['name' => 'sss', 'age' => 10, 'sex' => 22, 'ctime' => time(), 'utime' => time()],
				['name' => 'ssss', 'age' => 10, 'sex' => 22, 'ctime' => time(), 'utime' => time()],
			]
		);
	}
}
