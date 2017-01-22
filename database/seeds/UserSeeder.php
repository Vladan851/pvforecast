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
        //
		$insert = [];
		$insert[0] = [
				'name' => 'Vladan M',
				'email' => 'vladan.mastilovic@gmail.com',
			    'email1' => 'djurdjica.tegaric@gmail.com',
				'password' => bcrypt('akademija851'),
			];
		$insert[1] = [
				'name' => 'Solar 1',
				'email' => 'solar1bileca@gmail.com',
			    'email1' => '',
				'password' => bcrypt('akademija851'),
			];
		DB::table('users')->insert($insert);
    }
}
