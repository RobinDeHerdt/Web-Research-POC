<?php 
	include 'PhpSerial.php';

	if(isset($_POST['data']))
	{ 
		$movesArray = [];

		foreach ($_POST['data'] as $key => $value) {
			array_push($movesArray, $value);
		}
		
		$json = json_encode($movesArray);
		echo $json;
	}

	if(isset($_POST['ledon']))
	{ 
		// Let's start the class
		$serial = new PhpSerial;

		// First we must specify the device. This works on both linux and windows (if
		// your linux serial device is /dev/ttyS0 for COM1, etc)
		$serial->deviceSet("/dev/ttyACM0");

		// We can change the baud rate, parity, length, stop bits, flow control
		$serial->confBaudRate(9600);
		$serial->confParity("none");
		$serial->confCharacterLength(8);
		$serial->confStopBits(1);
		$serial->confFlowControl("none");

		// Then we need to open it
		$serial->deviceOpen();
		$serial->sendMessage("a");
	}

	if(isset($_POST['ledoff']))
	{ 
		// Let's start the class
		$serial = new PhpSerial;

		// First we must specify the device. This works on both linux and windows (if
		// your linux serial device is /dev/ttyS0 for COM1, etc)
		$serial->deviceSet("/dev/ttyACM0");

		// We can change the baud rate, parity, length, stop bits, flow control
		$serial->confBaudRate(9600);
		$serial->confParity("none");
		$serial->confCharacterLength(8);
		$serial->confStopBits(1);
		$serial->confFlowControl("none");

		// Then we need to open it
		$serial->deviceOpen();
		$serial->sendMessage("b");
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

		<button onclick="sendSerialOn();">Led on</button>
		<button onclick="sendSerialOff();">Led off</button>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
	var canvas 				= document.getElementById("canvas");
	var context 			= canvas.getContext("2d");

	var x					= 475;
	var y 					= 475;

	var distance 			= 50;
	var movesArray  		= [0];

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
		movesArray = [0];
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

	function sendSerialOn()
	{
		$.post("", { ledon: movesArray })
  		.done(function() {
  			console.log('SUCCESS');
		})
		.fail(function() {
			console.log('FAILED');
		});	
	}

	function sendSerialOff()
	{
		$.post("", { ledoff: movesArray })
  		.done(function() {
  			console.log('SUCCESS');
		})
		.fail(function() {
			console.log('FAILED');
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