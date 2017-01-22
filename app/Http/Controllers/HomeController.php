<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Forecast;

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
