<?php
	$conn = dbConnect();

	$firstOne = true;
	$sql = "SELECT * FROM configFiles WHERE ";
	foreach($listOfFields as $key => $value) {
		if($firstOne) {
			$sql .= $key . "=" . $_POST[$key];
			$firstOne = false;
		} else {
			$sql .= " AND " . $key . "=" . $_POST[$key];
		}
	}
	$sql .= ";";

	$result = $conn->query($sql); 		//is there an identical file already present in the db?
	if ($result === TRUE) { 			//if query worked
		
		if ($result->num_rows > 0) { 	//if query returned something, a similar file already exists.
			$row = $result->fetch_assoc();
			//set everything to unactive, and our file to active
			$sql = "UPDATE configFiles SET active=false WHERE active=true; UPDATE configFiles SET active=true WHERE id=".$row["id"].";";
			
		} else {
			//a similar file has not been found. Setting all active to unactive and inserting our new file
			$sql = "UPDATE configFiles SET active=false WHERE active=true; INSERT INTO configFiles(fileName, active, station, RCFSeverityFilter, RCFRxIP_Address, RCFRxPortNo, RCFRxSocketType, RCFRxIOTimeout, RCFRxConnectionTimeout, RCFRxRetryDelay, RCFStationShortName, RCFReceiverPositionX, RCFReceiverPositionY, RCFReceiverPositionZ, GRCSMSeverityFilter, GRDSMSeverityFilter, GRDSMSampleRate, GESMSeverityFilter, ICMSeverityFilter, ICMTxIP_Address, ICMTxPortNo, ICMTxSocketType, ICMTxIOTimeout, ICMTxConnectionTimeout, ICMTxRetryDelay, ProcessingSeverityFilter, ProcessingDopplerTolerance, ProcessingFilterFreq, OutputSeverityFilter, OutputRootDirectory) VALUES (".$_POST['fileName'].", 1, ".$_POST['station'].", ".$_POST['RCFSeverityFilter'].", ".", ".$_POST['RCFRxIP_Address'].", ".$_POST['RCFRxPortNo'].", ".$_POST['RCFRxSocketType'].", ".$_POST['RCFRxIOTimeout'].", ".$_POST['RCFRxConnectionTimeout'].", ".$_POST['RCFRxRetryDelay'].", ".$_POST['RCFStationShortName'].", ".$_POST['RCFReceiverPositionX'].", ".$_POST['RCFReceiverPositionY'].", ".$_POST['RCFReceiverPositionZ'].", ".$_POST['GRCSMSeverityFilter'].", ".$_POST['GRDSMSeverityFilter'].", ".$_POST['GRDSMSampleRate'].", ".$_POST['GESMSeverityFilter'].", ".$_POST['ICMSeverityFilter'].", ".$_POST['ICMTxIP_Address'].", ".$_POST['ICMTxPortNo'].", ".$_POST['ICMTxSocketType'].", ".$_POST['ICMTxIOTimeout'].", ".$_POST['ICMTxConnectionTimeout'].", ".$_POST['ICMTxRetryDelay'].", ".$_POST['ProcessingSeverityFilter'].", ".$_POST['ProcessingDopplerTolerance'].", ".$_POST['ProcessingFilterFreq'].", ".$_POST['OutputSeverityFilter'].", ".$_POST['OutputRootDirectory'].");";
		}
		
		$result = $conn->query($sql);
	}
	
	$conn->close();
	
	/*if ($result === TRUE) {
		echo "Success";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}*/
	echo "dick";
?>
