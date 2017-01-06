<?php 
	$conn = new mysqli('localhost', 'root', 'root', 'web_research');
	
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	else
	{
		//echo 'connected!<br>';
	}

	$createQuery = "CREATE TABLE commands(id unsigned auto_increment primary_key, command_number int);";
	$deleteQuery = "TRUNCATE TABLE commands";
	// $insertQuery = "INSERT INTO commands(command_number) VALUES(3),(1),(2),(4)";
	$insertQuery = "INSERT INTO commands(command_number) VALUES(1),(2),(3),(4),(5),(6)";

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