<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Forecast;

class HomeController extends Controller
{
    //
    public function index()
    {
//       function csv_to_array($filename='', $delimiter=',')
//       {
//            if(!file_exists($filename) || !is_readable($filename))
//                return FALSE;
//
//            $header = NULL;
//            $data = array();
//            if (($handle = fopen($filename, 'r')) !== FALSE)
//            {
//                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
//                {
//                    if(!$header)
//                        $header = $row;
//                    else
//                        $data[] = array_combine($header, $row);
//                }
//                fclose($handle);
//            }
//            return $data;
//        }
//        
//        $csvFile = public_path().'/pvwatts.csv';
//
//        $forecast = csv_to_array($csvFile);
//       
//        foreach ($forecast as $f){
//            $m = $f['Month'];
//            $d = $f['Day'];
//            $h = $f['Hour'];
//            $pv = $f['AC System Output (W)'];
//        }
//        
//
//        var_export($forecast); //prikazuje niz
//        
//        exit();

        
        return view('home');
    }
	public function search(Request $request)
	{
		//
		if($request->ajax()){
			$input = $request->input();
		}      
        $forecasts = Forecast::where('year', $input['year'])->where('month', $input['month'])->where('day', $input['day'])->get();
		$pv = [];
		$h = [];
		$i = 0;
		foreach ($forecasts as $f){
			if($f->pv_output_correction!=0){
			$pv[$i] = $f->pv_output_correction;
			$h[$i] = $f->hour;
			$i++;
			}
		}
		$result = array_combine($h, $pv);
        return response()->json($result);
	}
}
