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
            'pv_output_max' => 0,
            'location_id' => '1'
            ];
        }

		$insert1 = [];
        foreach ($forecast as $f){
            $insert1[] = [
	        'year' => '2017',
            'month' => $f['Month'],
            'day' => $f['Day'],
            'hour' => $f['Hour'],
            'pv_output' => $f['AC System Output (W)'],
            'location_id' => '2'
            ];
        }

        DB::table('forecasts')->insert($insert);
		DB::table('forecasts')->insert($insert1);

        $locations = DB::table('locations')->get();
        foreach ($locations as $l) {
            $result = DB::table('forecasts')
                ->where('location_id', $l->id)
                ->select('year', 'month', 'day')
                ->distinct()
                ->get()
                ->toArray();
            //var_dump($result); exit();
            $i = 0;
            $data = [];
            $batch = [];
            $tmp = $keys = [];
            foreach ($result as $row) {
                //$k = $l->id . '-' . $row->year . '-' . $row->month . '-' . $row->day;
                /*$data[$k] = [
                    'location_id' => $l->id,
                    'year' => $row->year,
                    'month' => $row->month,
                    'day' => $row->day,
                    'hours' => []
                ];*/
                //$keys[] = $k;

                if ( !empty($batch) && ( $row->day == 1 || $row->day == 16 ) ) {

                    $k = $l->id . '-' . $row->year . '-' . ($row->day == 1 ? $row->month-1 : $row->month) . '-' . implode(' ', $batch);
                    $data[$k] = [
                        'location_id' => $l->id,
                        'year' => $row->year,
                        'month' => $row->month,
                        'days' => $batch,
                        'hours' => $tmp
                    ];
                    //var_dump($data);var_dump($keys);var_dump($tmp);exit();
                    //foreach ($keys as $onek) {
                    //    $data[$onek]['hours'] = $tmp;
                    //}
                    $batch = [];
                    $tmp = $keys = [];
                    $i = 0;
                }

                $batch[] = $row->day;

                $hours = DB::table('forecasts')
                    ->where('location_id', $l->id)
                    ->where('year', $row->year)
                    ->where('month', $row->month)
                    ->where('day', $row->day)
                    ->get()
                    ->toArray();
                //var_dump($hours);exit();

                foreach ($hours as $h) {
                    if ( (int) $h->pv_output && ( empty($tmp[$h->hour]) || $tmp[$h->hour] < $h->pv_output ) ) {
                        $tmp[$h->hour] = $h->pv_output;
                    }
                }
                $i++;


            }
            // last batch
            if (!empty($batch)) {
                //foreach ($keys as $onek) {
                //    $data[$onek]['hours'] = $tmp;
                //}
                $k = $l->id . '-' . $row->year . '-' . $row->month . '-' . implode(' ', $batch);
                $data[$k] = [
                    'location_id' => $l->id,
                    'year' => $row->year,
                    'month' => $row->month,
                    'days' => $batch,
                    'hours' => $tmp
                ];
            }

            foreach ($data as $key => $day) {
                echo("$key\n");
                foreach ($day['hours'] as $h => $p) {
                    DB::table('forecasts')
                        ->where([
                            ['location_id', $day['location_id']],
                            ['year', $day['year']],
                            ['month', $day['month']],
                            //['day', $day['day']],
                            ['hour', $h]
                        ])
                        ->whereIn('day', $day['days'])
                        ->update(['pv_output_max' => $p]);
                }

            }
        } // end foreach location

    }
}
