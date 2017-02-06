<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forecast;
use App\Models\Location;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$locs = Location::all()->toArray();
        return view('home')->with('locs', $locs);
        //return view('home');
    }

	public function search(Request $request)
	{
		//
		if($request->ajax()){
			$input = $request->input();
		}
        $forecasts = Forecast::where('location_id', $input['loc'])->where('year', $input['year'])->where('month', $input['month'])->where('day', $input['day'])->get();
		$pv = [];
		$h = [];
		$i = 0;
		foreach ($forecasts as $f){
			if($f->pv_output_correction!=0){
			$pv[$i] = $f->pv_output_correction/1000;
			$h[$i] = $f->hour;
			$i++;
			}
		}
		$result = array_combine($h, $pv);
        return response()->json($result);
		//
	}
}
