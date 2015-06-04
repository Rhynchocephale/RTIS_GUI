<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$firstOne = true;
$sql = "SELECT * FROM configFiles WHERE ";
foreach($key => $value in $listOfFields) {
	if($firstOne) {
		$sql .= $key . "=" . $_POST[$key];
		$firstOne = false;
	} else {
		$sql .= " AND " . $key . "=" . $_POST[$key];
	}
}

// ---------------------------------------------TO DO --------------------------------------
//Now that we know if there is a similar file: if yes, set it as active and exit
//if no, get the active ones, and set them to not active

$sql = "INSERT INTO configFiles(fileName, active, station, RCFSeverityFilter, RCFRxIP_Address, RCFRxPortNo, RCFRxSocketType, RCFRxIOTimeout, RCFRxConnectionTimeout, RCFRxRetryDelay, RCFStationShortName, RCFReceiverPositionX, RCFReceiverPositionY, RCFReceiverPositionZ, GRCSMSeverityFilter, GRDSMSeverityFilter, GRDSMSampleRate, GESMSeverityFilter, ICMSeverityFilter, ICMTxIP_Address, ICMTxPortNo, ICMTxSocketType, ICMTxIOTimeout, ICMTxConnectionTimeout, ICMTxRetryDelay, ProcessingSeverityFilter, ProcessingDopplerTolerance, ProcessingFilterFreq, OuputSeverityFilter, OutputRootDirectory)
VALUES ("$_POST['fileName'].", 1, ".$_POST['station'].", ".$_POST['RCFSeverityFilter'].", ".", ".$_POST['RCFRxIP_Address'].", ".$_POST['RCFRxPortNo'].", ".$_POST['RCFRxSocketType'].", ".$_POST['RCFRxIOTimeout'].", ".$_POST['RCFRxConnectionTimeout'].", ".$_POST['RCFRxRetryDelay'].", ".$_POST['RCFStationShortName'].", ".$_POST['RCFReceiverPositionX'].", ".$_POST['RCFReceiverPositionY'].", ".$_POST['RCFReceiverPositionZ'].", ".$_POST['GRCSMSeverityFilter'].", ".$_POST['GRDSMSeverityFilter'].", ".$_POST['GRDSMSampleRate'].", ".$_POST['GESMSeverityFilter'].", ".$_POST['ICMSeverityFilter'].", ".$_POST['ICMTxIP_Address'].", ".$_POST['ICMTxPortNo'].", ".$_POST['ICMTxSocketType'].", ".$_POST['ICMTxIOTimeout'].", ".$_POST['ICMTxConnectionTimeout'].", ".$_POST['ICMTxRetryDelay'].", ".$_POST['ProcessingSeverityFilter'].", ".$_POST['ProcessingDopplerTolerance'].", ".$_POST['ProcessingFilterFreq'].", ".$_POST['OuputSeverityFilter'].", ".$_POST['OutputRootDirectory'].");";

if ($conn->query($sql) === TRUE) {
	echo "Success.";
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}	

$conn->close();
}
?>
