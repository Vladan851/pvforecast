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
		$return = [
			'status' => 'OK',
			'message' => ''
		];
        $locs = self::all();
		foreach ($locs as $l) {
			echo 'started ' . $l->name . "\n";
			$return['message'] .= 'Started ' . $l->name . '; ';
			$ok = false;
			$counter = 0;

			while (!$ok && $counter<5) {
				$json = file_get_contents($l->wunderground_api_call);
				if (empty($json)) {
					$counter++;
					$return['status'] = 'error';
					continue;
				}
				$hours = json_decode($json, true)['hourly_forecast'];
				if (empty($hours)) {
					$counter++;
					$return['status'] = 'error';
					continue;
				}
				$ok = true;
			}

			if (!$ok) continue;
			$return['status'] = 'OK';

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
				$pom->pv_output_max_correction = $pom->pv_output_max*(0.3 + (100 - $h['sky']) * 0.01);

				if($pom->save()){
	                echo "{$pom->location_id}, {$pom->id}, {$pom->year}, {$pom->month}, {$pom->day}, {$pom->hour}, {$pom->cloud_coverage}, {$pom->temperature}°C\n";
				}
				$return['message'] .= $pom->year . '-' . $pom->month . '-' . $pom->day . ': temp ' . $h['temp']['metric'] . '°C, sky ' . $h['sky'] . '%; ';
			}
			$return['message'] .= 'End ' . $l->name . '; ';
		} // end foreach location

		return $return;
    }

	/**
     * Get solar forecast from Renes
     */
    public static function updateRenes() {
		$return = [
			'status' => 'OK',
			'message' => ''
		];

        $locs = self::all();

		foreach ($locs as $l) {
			$return['message'] .= 'Started ' . $l->name . '; ';
			$ok = false;
			$counter = 0;

			while (!$ok && $counter < 5) {
				$xml = file_get_contents($l->renes_api_call);

				if (empty($xml)) {
					echo 'Empty response!!!';
					$return['status'] = 'error';
					$return['message'] .= 'empty response!; ';
					$counter++;
					continue;
				}

				$parsed = Parser::xml($xml);
				if (empty($parsed) || empty($parsed['tuple'])) {
					echo 'No TUPLE element!!!';
					$return['status'] = 'error';
					$return['message'] .= 'missing tuple element; ';
					$counter++;
					continue;
				}
				$ok = true;
			}

			if (!$ok) continue;

			$return['status'] = 'OK';

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

				$return['message'] .= 'power ' . $e['UTC_time'] . ': '.$e['PV_power_output'].'; ';
			}
			$return['message'] .= 'End ' . $l->name . '; ';
		} // end foreach location
		return $return;
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
				if (!empty($r->pv_output_renes) && (int) $r->pv_output_renes) {
					$temp['forecast'][$r->hour] = round($r->pv_output_renes/1000, 3);
					$total += round($r->pv_output_renes/1000, 3);

				} elseif (!empty($r->pv_output_correction) && (int) $r->pv_output_max_correction) {
					$temp['forecast'][$r->hour] = round($r->pv_output_max_correction/1000, 3);
					$total += round($r->pv_output_correction/1000, 3);
				}
			}
			$temp['total'] = $total;

			$data['days'][] = $temp;
		}
		//var_dump($data); exit();
		return $data;

	}
}
