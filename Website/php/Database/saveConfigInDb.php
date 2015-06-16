<?php
//Saving a file in the database

//Connection to db
include("database.php");
$conn = dbConnect();

//adding notice and/or debug to the value of the severity filters, if the relevant checkboxes are checked.
foreach($severityFilters as $key) {
	if(isset($_POST[$key . "SeverityFilterNotice"])) {
		$_POST[$key . "SeverityFilter"] += 16;
	}
	
	if(isset($_POST[$key . "SeverityFilterDebug"])) {
		$_POST[$key . "SeverityFilter"] += 128;
	}
}

//creation of the first SQL query, that will check that no other identical config already exists in the database
//basically, it checks all the relevant fields
$firstOne = true;
$sql = "SELECT * FROM configFiles WHERE ";
foreach($listOfFields as $key) {
	//removing unwanted fields
	if($key != "active" && $key != "fileName" && $key != "id" && $key != "date") {
		//not adding the "AND" the first time
		if($firstOne) { 						
			$sql .= $key . " = " . $_POST[$key];
			$firstOne = false;
		} else {
			//adding quotes if not numeric
			$sql .= " AND " . $key . " = " . (is_numeric($_POST[$key]) ? $_POST[$key] : "'".$_POST[$key]."'"); 
		}
	}
}
$sql .= ";";
//is there an identical file already present in the db?
$result = mysqli_query($conn, $sql);

//if query worked
if ($result) {
	//sets all files to "not active"
	$sql = "UPDATE configFiles SET active = false WHERE active = true; ";
	$result2 = mysqli_query($conn, $sql);
	
	//if first query returned something, a similar file already exists.
	if (mysqli_num_rows($result) != 0) {
		$row = mysqli_fetch_assoc($result);
		//sets everything to unactive, and the file found to active
		$sql = "UPDATE configFiles SET active = true WHERE id = ".$row["id"].";";
	} else {
		//a similar file has not been found. Inserting our new file
		$sql = "INSERT INTO configFiles(fileName, active, station, RCFSeverityFilter, RCFRxIP_Address, RCFRxPortNo, RCFRxSocketType, RCFRxIOTimeout, RCFRxConnectionTimeout, RCFRxRetryDelay, RCFStationShortName, RCFReceiverPositionX, RCFReceiverPositionY, RCFReceiverPositionZ, GRCSMSeverityFilter, GRDSMSeverityFilter, GRDSMSampleRate, GESMSeverityFilter, ICMSeverityFilter, ICMTxIP_Address, ICMTxPortNo, ICMTxSocketType, ICMTxIOTimeout, ICMTxConnectionTimeout, ICMTxRetryDelay, ProcessingSeverityFilter, ProcessingDopplerTolerance, ProcessingFilterFreq, OutputSeverityFilter, OutputRootDirectory) VALUES ("."'".$_POST['fileName']."'".", true, ".$_POST['station'].", ".$_POST['RCFSeverityFilter'].", "."'".$_POST['RCFRxIP_Address']."'".", ".$_POST['RCFRxPortNo'].", ".$_POST['RCFRxSocketType'].", ".$_POST['RCFRxIOTimeout'].", ".$_POST['RCFRxConnectionTimeout'].", ".$_POST['RCFRxRetryDelay'].", "."'".$_POST['RCFStationShortName']."'".", ".$_POST['RCFReceiverPositionX'].", ".$_POST['RCFReceiverPositionY'].", ".$_POST['RCFReceiverPositionZ'].", ".$_POST['GRCSMSeverityFilter'].", ".$_POST['GRDSMSeverityFilter'].", ".$_POST['GRDSMSampleRate'].", ".$_POST['GESMSeverityFilter'].", ".$_POST['ICMSeverityFilter'].", "."'".$_POST['ICMTxIP_Address']."'".", ".$_POST['ICMTxPortNo'].", ".$_POST['ICMTxSocketType'].", ".$_POST['ICMTxIOTimeout'].", ".$_POST['ICMTxConnectionTimeout'].", ".$_POST['ICMTxRetryDelay'].", ".$_POST['ProcessingSeverityFilter'].", ".$_POST['ProcessingDopplerTolerance'].", ".$_POST['ProcessingFilterFreq'].", ".$_POST['OutputSeverityFilter'].", "."'".$_POST['OutputRootDirectory']."'".");";
	}
	
	$result = mysqli_query($conn, $sql);
} 

if ($result) {
	echo "Success";
} else {
	echo "Error on request: " . $sql . "\nThe error is:" . mysqli_error($conn);
}

mysqli_close($conn); 
?>
