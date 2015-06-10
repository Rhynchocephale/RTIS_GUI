<?php
	include("database.php");

	$conn = dbConnect();
	$sql = "SELECT * FROM configFiles WHERE id = " . $_GET["selectedOption"] . ";";
	$result = mysqli_query($conn, $sql);
	mysqli_close($conn);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		foreach($row as $key => $value) {
			$data[$key] = $value;
		}
	}
	
	echo json_encode($data);
?>
