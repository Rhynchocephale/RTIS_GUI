<?php
	$conn = dbConnect();
	$sql = "SELECT * FROM configFiles WHERE id = " . $_GET["selectConfig"] . ";";
	$result = $conn->query($sql);
	$conn->close();

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		foreach($row as $key => $value) {
			$data[$key] = addslashes($value);
		}
	}
	
	echo json_encode($data);
?>
