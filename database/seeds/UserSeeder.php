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
		$insert = [
			[
				'name' => 'Vladan M',
				'email' => 'dinecodoo@me.com',
			    'email1' => 'draganand79@gmail.com',
				'password' => bcrypt('akademija851'),
			],
			[
				'name' => 'Solar 1',
				'email' => 'solar1bileca@gmail.com',
			    'email1' => '',
				'password' => bcrypt('akademija851'),
			],
			[
				'name' => 'Zoki',
				'email' => 'zv1985@gmail.com',
			    'email1' => '',
				'password' => bcrypt('beograd2017'),
			]
		];
		DB::table('users')->insert($insert);
    }
}
