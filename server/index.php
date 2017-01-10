<?php
	$conn = new mysqli('localhost', 'root', 'root', 'web_research');
		
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM commands";

	$resultsArray = [];

	if($result = $conn->query($sql))
	{
		foreach ($result as $key => $value) {
			array_push($resultsArray, $value['command_number']); 
		}
		$result->close();
	}

	$conn->close();

	$json = json_encode($resultsArray);
	echo $json;
?>