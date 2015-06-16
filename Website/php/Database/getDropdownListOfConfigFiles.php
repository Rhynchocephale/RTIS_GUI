<?php
//getting the list of the database entries where "station" equals the desired station, to put them in a dropdown list

include("database.php");

//connecting to the db
$conn = dbConnect();
$sql = "SELECT * FROM configFiles WHERE station = " . $_GET["sta"] . " ORDER BY date DESC;";
$result = mysqli_query($conn, $sql);

if($result) {
	$listOfValues = "";
	if (mysqli_num_rows($result) > 0) {
		// looking for the active file, putting it first
		while($row = mysqli_fetch_assoc($result)) {
			if($row["active"]) {
				//setting the string to display
				$listOfValues .= "<option value=\"" . $row["id"] . "\" selected=\"selected\">(ACTIVE) ". $row["fileName"]. ($row["fileName"]?", ":"") . $row["date"] . "</option>\n";
				
				//setting the selected option to match the one we found (on page load)
				$_GET["selectedOption"] = $row["id"];
				
				//only one active file exists
				break;
			}
		}
		
		// set the pointer back to the beginning
		mysqli_data_seek($result, 0);
		// looping again, for the other files
		while($row = mysqli_fetch_assoc($result)) {
			if(!$row["active"]) { 						//only unactive files, as the active one has already been found
				if(!isset($_GET["selectedOption"])) { 	//if no active config file has been found, take the first file in the list				
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
