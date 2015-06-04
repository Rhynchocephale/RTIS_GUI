<?php

$conn = dbConnect();
$sql = "SELECT * FROM configFiles WHERE id = " . $_POST["selectConfig"] . ";";
$result = $conn->query($sql);
$conn->close();

$listOfFields = ["fileName", "station", "RCFSeverityFilter", "RCFRxIP_Address", "RCFRxPortNo", "RCFRxSocketType", "RCFRxIOTimeout", "RCFRxConnectionTimeout", "RCFRxRetryDelay", "RCFStationShortName", "RCFReceiverPositionX", "RCFReceiverPositionY", "RCFReceiverPositionZ", "GRCSMSeverityFilter", "GRDSMSeverityFilter", "GRDSMSampleRate", "GESMSeverityFilter", "ICMSeverityFilter", "ICMTxIP_Address", "ICMTxPortNo", "ICMTxSocketType", "ICMTxIOTimeout", "ICMTxConnectionTimeout", "ICMTxRetryDelay", "ProcessingSeverityFilter", "ProcessingDopplerTolerance", "ProcessingFilterFreq", "OuputSeverityFilter", "OutputRootDirectory"];
if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	foreach($row as $key => $value) {
		eval('$'.$key.' = '.addslashes($value).';');
	}
}

$_POST['text'] = 'another value';
?>
