<?php

$conn = dbConnect();
$sql = "SELECT * FROM configFiles WHERE station = " . $_GET["sta"] . ";";
$result = $conn->query($sql);
$conn->close();

$listOfValues = "";
if ($result->num_rows > 0) {
	// looking for the active file, putting it first
	while($row = $result->fetch_assoc()) {
		if($row["active"]) {
			$listOfValues .= "<option value=\"" . $row["id"] . "\">(ACTIVE)". $row["name"]. ($row["name"]?", ":"") . date("j-n-y, G\h", $row["date"]). "</option>\n";
			$_POST["selectConfig"] = $row["id"];
			include("prefillFields.php"); //prefilling the forms with the active file
			break;
		}
	}
	// looping again, for the other files
	while($row = $result->fetch_assoc()) {
		if(!$row["active"]) {
			$listOfValues .= "<option value=\"" . $row["id"] . "\">". $row["name"]. ($row["name"]?", ":"") . date("j-n-y, G\h", $row["date"]). "</option>\n";
			if(!isset($_POST["selectConfig"])) { //if no active config file is found, take the first file in the list				
				$_POST["selectConfig"] = $row["id"];
				include("prefillFields.php"); //prefilling the forms with the file
			}
		}
	}
	
} else {
	$listOfValues = "<option value=\"-1\">No file found</option>\n";
}
?>
