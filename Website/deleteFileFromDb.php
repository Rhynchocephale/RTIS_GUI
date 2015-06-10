<?php
	include("database.php");

	$conn = dbConnect();
	$sql = "DELETE FROM configFiles WHERE id = " .  $_GET['selectedOption'] . ";";
	mysqli_query($conn, $sql);
	mysqli_close($conn);
?>
