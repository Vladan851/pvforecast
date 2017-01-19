
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    </head>
    <body>   
			<p>Choose a date for solar forecast!</p>
			
			Date:<input type="text" id="datepicker">
	

	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<script>
	$(function() {
	  $("#datepicker").datepicker();
	}).on("change", function() {
		var date = document.getElementById("datepicker").value;
		var par = date.split('/');
		var month = par[0];
		var day = par[1];
		var year = par[2];
		$.ajax({
            url: "/search",
			data: {
				month: month,
				day: day,
				year: year
			},
			type: 'get',
            dataType: 'json',
        }).done(function (data) {
			var str = JSON.stringify(data);
			console.log(data);
			display(str);
			//display(data);
        }).fail(function () {
            console.log("Problem!");
        });
		display(date);
    });
	function display(msg) {
    $("<p>").html(msg).appendTo(document.body);
	
    }
	
	</script>
    </body>
</html>