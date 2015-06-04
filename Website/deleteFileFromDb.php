<?php
$fileToDelete = $_POST['selectConfig'];

$conn = dbConnect();
if( $fileToDelete >= 0 ) { // prevents from erasing things like "No files found"
	$conn = dbConnect();
	$sql = "DELETE FROM configFiles WHERE id = " . $fileToDelete . ";";
	$result = $conn->query($sql);
}
$conn->close();

include("getDropdownListOfConfigFiles.php");

?>
