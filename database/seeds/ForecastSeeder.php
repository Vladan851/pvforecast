<?php

use Illuminate\Database\Seeder;

class ForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
       function csv_to_array($filename='', $delimiter=',')
       {
            if(!file_exists($filename) || !is_readable($filename))
                return FALSE;

            $header = NULL;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== FALSE)
            {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
                {
                    if(!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }
            return $data;
        }
        
        $csvFile = public_path().'/pvwatts.csv';

        $forecast = csv_to_array($csvFile);
        $insert = [];
        foreach ($forecast as $f){
            $insert[] = [
	        'year' => '2017',
            'month' => $f['Month'],
            'day' => $f['Day'],
            'hour' => $f['Hour'],
            'pv_output' => $f['AC System Output (W)'],
            'location_id' => '1'
            ];
        }
        
        DB::table('forecasts')->insert($insert);
    
    
//        $insert[] = [
//            'month' => '1',
//            'day' => '1',
//            'hour' => '12',
//            'pv_output' => '42.37',
//            'location_id' => '1'
//        ];
//   	  DB::table('forecast')->insert($insert);
    }
}
