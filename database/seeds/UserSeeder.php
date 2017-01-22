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
		$insert[] = [
				'name' => 'Vladan M',
				'email' => 'vladan.mastilovic@gmail.com',
				'password' => 'akademija851'
			];
		DB::table('users')->insert($insert);
    }
}
