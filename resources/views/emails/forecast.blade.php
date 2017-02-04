<html>
<head>
</head>
<body>
	@foreach($data['days'] as $day)
		<h1>Predikcija proizvodnje za {{ $day['string'] }}</h1>
		<h2>{{ $data['name'] }}</h2>
		<br/>
		<h2>Jedna elektrana</h2><br/>
		<table border='1' cellspacing='0' cellpadding='3'>
			<thead>
				<tr>
					@foreach ($day['forecast'] as $key => $val)
					<th>{{ $key }}:00</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				<tr>
					@foreach ($day['forecast'] as $key => $val)
					<td>{{ $val }}</td>
					@endforeach
				</tr>
			</tbody>
		</table>
		<br/><br/>
		<span>Ukupno: <b>{{ $day['total'] }} kWh</b></span>
		<br/><hr/>
		<h2>Sve elektrane</h2><br/>
		<table border='1' cellspacing='0' cellpadding='3'>
			<thead>
				<tr>
					@foreach ($day['forecast'] as $key => $val)
					<th>{{ $key }}:00</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				<tr>
					@foreach ($day['forecast'] as $key => $val)
					<td>{{ $val*4 }}</td>
					@endforeach
				</tr>
			</tbody>
		</table>
		<br/><br/>
		<span>Ukupno: <b>{{ $day['total']*4 }} kWh</b></span>
		<br/><hr/>

	@endforeach
</body>
</html>
