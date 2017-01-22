<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Forecast;
use App\Location;

class EmailController extends Controller
{
    //
	public function send(Request $request){
    //Logic will go here        
		//$currentDate = Date("Y/m/d");
		$currentDate1 = Date('Y/n/j', strtotime("+1 day"));
		$currentDate2 = Date('Y/n/j', strtotime("+2 days"));
		$currentDate3 = Date('Y/n/j', strtotime("+3 days"));
		
		$locations = Location::all();
		foreach ($locations as $loc){
			$location = $loc->name.', '.$loc->dc_size.'kW';
			
			$email = $loc->user->email;
			
			$forecasts1 = $loc->forecasts->where('year', date("Y",strtotime($currentDate1)))->where('month', date('n',strtotime($currentDate1)))->where('day', date('j', strtotime($currentDate1)));
			$forecasts2 = $loc->forecasts->where('year', date("Y",strtotime($currentDate2)))->where('month', date('n', strtotime($currentDate2)))->where('day', date('j', strtotime($currentDate2)));
			$forecasts3 = $loc->forecasts->where('year', date("Y",strtotime($currentDate3)))->where('month', date('n', strtotime($currentDate3)))->where('day', date('j', strtotime($currentDate3)));

			$pv1 = [];
			$h1 = [];
			$i = 0;
			foreach ($forecasts1 as $f){
				if($f->pv_output_correction!=0){
				$h1[$i] = $f->hour;
				$pv1[$i] = $f->pv_output_correction;
				$i++;
				}
			}
			$pv2 = [];
			$h2 = [];
			$j = 0;
			foreach ($forecasts2 as $f){
				if($f->pv_output_correction!=0){
				$h2[$j] = $f->hour;
				$pv2[$j] = $f->pv_output_correction;
				$j++;
				}
			}
			$pv3 = [];
			$h3 = [];
			$k = 0;
			foreach ($forecasts3 as $f){
				if($f->pv_output_correction!=0){
				$h3[$k] = $f->hour;
				$pv3[$k] = $f->pv_output_correction;
				$k++;
				}
			}
			Mail::send('emails.send', ['loc' => $location,'date1' => $currentDate1,'hours1' => $h1, 'pvs1' => $pv1,'date2' => $currentDate2,'hours2' => $h2, 'pvs2' => $pv2,'date3' => $currentDate3,'hours3' => $h3, 'pvs3' => $pv3], function ($message) use ($email, $location)
			{

            $message->from('inverterlog@gmail.com', 'InverterLog');

            $message->to($email);
			
			$message->subject("Three day forecast for: ".$location);

			});
			return response()->json(['message' => 'Message sent!']);
			
			}

	}
}
