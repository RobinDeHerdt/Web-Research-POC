<?php 
	$conn = new mysqli('localhost', 'root', 'root', 'web_research');
	
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$createQuery = "CREATE TABLE commands(id unsigned auto_increment primary_key, command_number int);";
	$deleteQuery = "TRUNCATE TABLE commands";

	if(isset($_POST['data']))
	{ 
		$queryString = "";

		foreach ($_POST['data'] as $key => $value) {
			if($key != count($_POST['data']) - 1)
			{
				$queryString .= "(" . $value . "),";
			}
			else
			{
				$queryString .= "(" . $value . ")";
			}
		}

		$insertQuery = "INSERT INTO commands(command_number) VALUES" . $queryString;

		if($conn->query($deleteQuery) === true)
		{
			//echo 'Old records deleted.<br>';
		}
		else
		{
			echo 'Error' . $deleteQuery . '<br>' . $conn->error . '<br>';
		}

		if($conn->query($insertQuery) === true)
		{
			//echo 'New records created.<br>';
		}
		else
		{
			echo 'Error' . $insertQuery . '<br>' . $conn->error . '<br>';
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		.container {
			text-align: center;
		}

		#canvas {
			border: 1px solid black;
		}

		#steering button {
			padding: 10px;
			margin-left: 5px;
		}
	</style>
</head>
<body>
<div class="container">
	<canvas id="canvas" width="500" height="500"></canvas>
	<div id="steering">
		<button onclick="up();">Up</button>
		<button onclick="down();">Down</button>
		<button onclick="right();">Right</button>
		<button onclick="left();">Left</button>
		<button onclick="reset();">Reset</button>
		<button onclick="send();">Send</button>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
	var canvas 				= document.getElementById("canvas");
	var context 			= canvas.getContext("2d");

	var x					= 475;
	var y 					= 475;

	var distance 			= 50;
	var movesArray  		= [];

	var previousDirection 	= 1;
	
	context.moveTo(x, y);

	// 1
	function up()
	{
		x = x;
		if(y - distance >= 0)
		{
			y = y - distance;

			switch(previousDirection)
			{
				case 1: 
					movesArray.push(1);
					break; 
				case 2: 
					movesArray.push(3,1);
					break;
				case 3: 
					movesArray.push(2,1);
					break;
				case 4: 
					movesArray.push(3,3,1);
			}
			previousDirection = 1;
			console.log(movesArray);
		}
	
		draw();
	}

	// 2
	function right()
	{
		if(x + distance <= canvas.width)
		{
			x = x + distance;

			switch(previousDirection)
			{
				case 1: 
					movesArray.push(3,1);
					break; 
				case 2: 
					movesArray.push(1);
					break;
				case 3: 
					movesArray.push(3,3,1);
					break;
				case 4: 
					movesArray.push(2,1);
			}
			previousDirection = 2;
			console.log(movesArray);
		}
		y = y;

		draw();
	}

	// 3
	function left()
	{
		if(x - distance >= 0)
		{
			x = x - distance;
			
			switch(previousDirection)
			{
				case 1: 
					movesArray.push(3,1);
					break; 
				case 2: 
					movesArray.push(2,2,1);
					break;
				case 3: 
					movesArray.push(1);
					break;
				case 4: 
					movesArray.push(3,1);
			}
			previousDirection = 3;
			console.log(movesArray);
		}
		y = y;

		draw();
	}

	function down()
	{
		x = x;
		if(y + distance <= canvas.height)
		{
			y = y + distance;

			switch(previousDirection)
			{
				case 1: 
					movesArray.push(2,2,1);
					break; 
				case 2: 
					movesArray.push(2,1);
					break;
				case 3: 
					movesArray.push(3,1);
					break;
				case 4: 
					movesArray.push(1);
			}
			previousDirection = 4;
			console.log(movesArray);
		}

		draw();
	}

	function reset()
	{
		x = 475;
		y = 475;
		movesArray = [];
		context.clearRect(0, 0, canvas.width, canvas.height);
		context.beginPath();
		context.moveTo(x, y);
	}

	function send() 
	{
		$.post("", { data: movesArray })
  		.done(function() {
  			console.log('Success!');
		})
		.fail(function() {
			console.log('Something went wrong :(');
		});	
	}

	function draw()
	{
		context.lineTo(x, y);
		context.stroke();
	}
</script>
</body>
</html>