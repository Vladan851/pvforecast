<html>
<head>
	<style>
	table, th, td {
		border: 1px solid black;
	}
	</style>
</head>
<body>
	<h1>{{$loc}}</h1>
	<h3>{{$date1}}</h3>
	<table style="width:100%">
	  <tr>
		@foreach ($hours1 as $h1)
		<th>{{ $h1 }}-{{ $h1+1 }}</th>
		@endforeach
	  </tr>
	  <tr>
		@foreach ($pvs1 as $pv1)
		<td>{{ $pv1/1000 }}</td>
		@endforeach
	  </tr>
	</table>
	<p>Ukupna proizvodnja za, {{$date1}}: {{$sum1}}</p>
	<h3>{{$date2}}</h3>
	<table style="width:100%">
	  <tr>
		@foreach ($hours2 as $h2)
		<th>{{ $h2 }}-{{ $h2+1 }}</th>
		@endforeach
	  </tr>
	  <tr>
		@foreach ($pvs2 as $pv2)
		<td>{{ $pv2/1000 }}</td>
		@endforeach
	  </tr>
	</table>
	<p>Ukupna proizvodnja za, {{$date2}}: {{$sum2}}</p>
	<h3>{{$date3}}</h3>
	<table style="width:100%">
	  <tr>
		@foreach ($hours3 as $h3)
		<th>{{ $h3}}-{{ $h3+1 }}</th>
		@endforeach
	  </tr>
	  <tr>
		@foreach ($pvs3 as $pv3)
		<td>{{ $pv3/1000 }}</td>
		@endforeach
	  </tr>
	</table>
	<p>Ukupna proizvodnja za, {{$date3}}: {{$sum3}}</p>
	<p><span>All prodaction values are in kW.</span></p>
</body>
</html>