<?php 
	include 'PhpSerial.php';

	if(isset($_POST['data']))
	{ 
		$movesArray = [];

		foreach ($_POST['data'] as $key => $value) {
			array_push($movesArray, $value);
		}
		
		$json = json_encode($movesArray);

		$serial = new PhpSerial;

		// Linux
		$serial->deviceSet("/dev/ttyACM0");

		$serial->confBaudRate(9600);
		$serial->confParity("none");
		$serial->confCharacterLength(8);
		$serial->confStopBits(1);
		$serial->confFlowControl("none");

		$serial->deviceOpen();
		sleep(3);
		$serial->sendMessage($json);
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Draw</title>
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
		<span id="send-callback"></span>
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
		}
	
		draw();
	}

	function right()
	{
		if(x + distance <= canvas.width)
		{
			x = x + distance;

			switch(previousDirection)
			{
				case 1: 
					movesArray.push(2,1);
					break; 
				case 2: 
					movesArray.push(1);
					break;
				case 3: 
					movesArray.push(3,3,1);
					break;
				case 4: 
					movesArray.push(3,1);
			}
			previousDirection = 2;
		}
		y = y;

		draw();
	}

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
		}

		draw();
	}

	function reset()
	{
		x = 475;
		y = 475;
		movesArray = [];
		previousDirection = 1;
		context.clearRect(0, 0, canvas.width, canvas.height);
		context.beginPath();
		context.moveTo(x, y);
	}

	function send() 
	{
		var element = document.getElementById('send-callback');

		$.post("", { data: movesArray })
  		.done(function() {
  			element.innerHTML = "Sent!";
		})
		.fail(function() {
			element.innerHTML = "Failed :(";
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