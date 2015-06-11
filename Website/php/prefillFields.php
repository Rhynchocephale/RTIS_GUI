<?php
	//connection to the database
	include("database.php");
	
	$conn = dbConnect();
	//getting the data about the desired file
	$sql = "SELECT * FROM configFiles WHERE id = " . $_GET["selectedOption"] . ";";
	$result = mysqli_query($conn, $sql);
	mysqli_close($conn);

	//inserting all fields and their value in an associative array
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		foreach($row as $key => $value) {
			$data[$key] = $value;
		}
	}
	
	echo json_encode($data);
?>
