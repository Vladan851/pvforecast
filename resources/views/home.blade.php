@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body" id="pcx">
					<p>Choose a location and date for solar forecast!</p>
			
				   Location:<select id="loc-search" placeholder="Choose location">
								<option value=""></option>
								@foreach ($locs as $loc)
									<option value="{{ $loc['id'] }}">{{ $loc['name'] }}</option>
								@endforeach
							</select>
				   Date:<input type="text" id="datepicker">
				   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
