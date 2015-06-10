<?php
include("database.php");

$conn = dbConnect();
$sql = "SELECT * FROM configFiles WHERE station = " . $_GET["sta"] . ";";
$result = mysqli_query($conn, $sql);

if($result) {
	$listOfValues = "";
	if (mysqli_num_rows($result) > 0) {
		// looking for the active file, putting it first
		while($row = mysqli_fetch_assoc($result)) {
			if($row["active"]) {
				$listOfValues .= "<option value=\"" . $row["id"] . "\" selected=\"selected\">(ACTIVE) ". $row["fileName"]. ($row["fileName"]?", ":"") . $row["date"] . "</option>\n";
				$_GET["selectedOption"] = $row["id"];
				break;
			}
		}
		
		// set the pointer back to the beginning
		mysqli_data_seek($result, 0);
		// looping again, for the other files
		while($row = mysqli_fetch_assoc($result)) {
			if(!$row["active"]) {
				if(!isset($_GET["selectedOption"])) { //if no active config file is found, take the first file in the list				
					$_GET["selectedOption"] = $row["id"];
					$listOfValues .= "<option value=\"" . $row["id"] . "\" selected=\"selected\">". $row["fileName"]. ($row["fileName"]?", ":"") . $row["date"] . "</option>\n";
				} else {
					$listOfValues .= "<option value=\"" . $row["id"] . "\">". $row["fileName"]. ($row["fileName"]?", ":"") . $row["date"] . "</option>\n";
				}
			}
		}
		
	} else { //if number of rows found = 0, no file in DB
		$listOfValues = "<option value=\"-1\">No file found</option>\n";
	}
	
	echo $listOfValues;
} else {
	 echo "Error: " . $sql . "\n" . mysqli_error($conn);
}
mysqli_close($conn);
?>
