<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nathanmac\Utilities\Parser\Facades\Parser;

class Location extends Model
{
    //
	public function forecasts()
	{
		return $this->hasMany('App\Models\Forecast');
	}

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	/**
     * Get weather from Wunderground
     */
    public static function updateWeather() {
        $locs = self::all();

		foreach ($locs as $l) {
			$json = file_get_contents($l->wunderground_api_call);
			$hours = json_decode($json, true)['hourly_forecast'];

			foreach ($hours as $h){
	            $pom = $l->forecasts()->where([
						['year', $h['FCTTIME']['year']],
						['month', $h['FCTTIME']['mon']],
						['day', $h['FCTTIME']['mday']],
						['hour', $h['FCTTIME']['hour']]
					])->first();

	            $pom->cloud_coverage = $h['sky'];
	            $pom->temperature = $h['temp']['metric'];
	            $pom->pv_output_correction = $pom->pv_output*(0.3 + (100 - $h['sky']) * 0.01);

				if($pom->save()){
	                echo "{$pom->location_id}, {$pom->id}, {$pom->year}, {$pom->month}, {$pom->day}, {$pom->hour}, {$pom->cloud_coverage}, {$pom->temperature}°C\n";
				}
			}
		} // end foreach location

		return true;
    }

	/**
     * Get solar forecast from Renes
     */
    public static function updateRenes() {
        $locs = self::all();

		foreach ($locs as $l) {
			$xml = @file_get_contents($l->renes_api_call);
			if (empty($xml)) {
				echo 'Empty response!!!';
				continue;
			}

			$parsed = Parser::xml($xml);
			if (empty($parsed) || empty($parsed['tuple'])) {
				echo 'No TUPLE element!!!';
				continue;
			}

			foreach ($parsed['tuple'] as $e) {
				$t = new \DateTime($e['UTC_time']);
				$t->setTimezone(new \DateTimeZone("Europe/Sarajevo"));

				$pom = $l->forecasts()->where([
						['year', $t->format('Y')],
						['month', $t->format('n')],
						['day', $t->format('j')],
						['hour', $t->format('G')]
					])->first();

	            $pom->pv_output_renes = $e['PV_power_output'] * 1000;

				if($pom->save()){
	                echo "{$pom->location_id}, {$pom->id}, {$pom->year}, {$pom->month}, {$pom->day}, {$pom->hour}, {$pom->pv_output_renes}Wh\n";
				} else { echo 'ERROR!'; }
			}
		} // end foreach location
    }

	public function getReportData ($days = 3) {
		$data = [
			'name' => $this->name,
			'days' => []
		];
		for ($i=1; $i<=$days; $i++) {
			$obj = new \DateTime("+$i day");
			$day = '';
			switch ($obj->format('D')) {
				case 'Mon':
					$day = 'ponedjeljak';
					break;
				case 'Tue':
					$day = 'utorak';
					break;
				case 'Wed':
					$day = 'srijeda';
					break;
				case 'Thu':
					$day = 'četvrtak';
					break;
				case 'Fri':
					$day = 'petak';
					break;
				case 'Sat':
					$day = 'subota';
					break;
				case 'Sun':
					$day = 'nedjelja';
					break;
			}
			$temp = [
				'name' => $this->name,
				'year' => $obj->format('Y'),
				'month' => $obj->format('n'),
				'day' => $obj->format('j'),
				'string' => "$day {$obj->format('Y/n/j')}",
				'forecast' => [],
				'total' => 0
			];

			$results = $this->forecasts()->where([
					['year', $temp['year']],
					['month', $temp['month']],
					['day', $temp['day']]
				])->get();

			$total = 0;
			foreach ($results as $r) {
				if (!empty($r->pv_output_renes)) {
					$temp['forecast'][$r->hour] = round($r->pv_output_renes/1000, 3);
					$total += round($r->pv_output_renes/1000, 3);
				} elseif (!empty($r->pv_output_correction)) {
					$temp['forecast'][$r->hour] = round($r->pv_output_correction/1000, 3);
					$total += round($r->pv_output_correction/1000, 3);
				}
			}
			$temp['total'] = $total;

			$data['days'][] = $temp;
		}
		//var_dump($data); exit();
		return $data;


		///// Preksutra //////////////////////////
		$text .= "<h1>Predikcija proizvodnje za preksutra ($tom2Date)</h1>
				<h2>".$solar->name."</h2>
				<h3><b>$tom2Date</b><h3>
				<br/><br/>
				<h2>Jedna elektrana</h2><br/>
				<table border='1' cellspacing='0' cellpadding='3'>
					<thead>
						<tr>";

		foreach ($tom2 as $k => $v) {
			$time = new \DateTime($k);
			$text .= "<th>". $time->format("H:i") . "</th>";
		}

		$text .= "</tr></thead><tbody><tr>";

		$total = 0;
		foreach ($tom2 as $k => $v) {
			$text .= "<td>$v</td>";
			$total += $v;
		}
		$text .= "</tr></tbody></table>
				<br/><br/>
				Ukupno preksutra ($tom2Date): ".round($total, 2)." kWh<br/><hr/>
				<h2>Sve elektrane</h2><br/>
				<table border='1' cellspacing='0' cellpadding='3'>
					<thead>
						<tr>
				";


		foreach ($tom2 as $k => $v) {
			$time = new \DateTime($k);
			$text .= "<th>". $time->format("H:i") . "</th>";
		}

		$text .= "</tr></thead><tbody><tr>";

		$total2 = 0;
		foreach ($tom2 as $k => $v) {
			$text .= "<td>". 4*$v . "</td>";
			$total2 += 4*$v;
		}
		$text .= "</tr></tbody></table>
				<br/><br/>
				Ukupno preksutra ($tom2Date): ".round($total2, 2)." kWh

				<br/><br/>";


		$text .= "<b>Napomena:</b> Sve vrijednosti su u kWh!
				<br/>
				<p><strong>InverterLog Tim</strong></p>
				";


		//debug($text);
		//$email = 'zv1985@gmail.com';
		$e = new Email();
		$e->domain('inverterterlog.com')
			->emailFormat('html')
			->subject($sub)
			->to($solar->email)
			->from('reports@inverterlog.com')
			->replyTo('inverterlog@gmail.com');

		$additional = ['zv1985@gmail.com', 'vladan.mastilovic@gmail.com'];
		if (!empty($solar->email2)) {
			if (!in_array($solar->email2, $additional)) {
				$additional[] = $solar->email2;
			}
		}
		if (!empty($solar->email3)) {
			if (!in_array($solar->email3, $additional)) {
				$additional[] = $solar->email3;
			}
		}

		foreach ($additional as $a) {
			$e->addTo($a);
		}

		$e->send($text);

	}
}
