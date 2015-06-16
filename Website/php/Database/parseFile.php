<?php
//max size of the file (in kB)
$maxSize = 3;
$uploadOk = 1;
$errorMsg = "";

foreach($_FILES as $file) { //there should only be one, but also at least one
	
	if(!$file["name"]) { 	//if name is empty, nothing has been uploaded
		return;
	}
	
	$fileType = pathinfo(basename($file["name"]),PATHINFO_EXTENSION);
	
	// Check file size (no more than maxSize kB)
	if ($file["size"] > $maxSize*1000) {
		$errorMsg .= "Error: your file is too large. ";
		$uploadOk = 0;
	}

	// Allow certain file formats
	if($fileType != "cfg") {
		$errorMsg .= "Error: only .cfg files are allowed.";
		$uploadOk = 0;
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo $errorMsg;
		
	// if everything is ok, read file
	} else {
		$fp = fopen($file['tmp_name'], 'rb');
		$data = parseValues($fp);
		fclose($fp);
		
		if(!$errorMsg) { 				//everything went fine
			echo json_encode($data);
		} else {
			echo json_encode($errorMsg); //encoding allows to escape characters
		}
	}
}


function parseValues($fp){
	global $errorMsg;
	$data = [];
	
	//getting all the lines, one by one
	$data = getIntLine($data,"RCF","SeverityFilter");
	$data = getIntLine($data,"RCF","StationId");
	$data = getIpLine($data,"RCF","RxIP_Address");
	$data = getIntLine($data,"RCF","RxPortNo");
	$data = getIntLine($data,"RCF","RxSocketType");
	$data = getIntLine($data,"RCF","RxIOTimeout");
	$data = getIntLine($data,"RCF","RxConnectionTimeout");
	$data = getIntLine($data,"RCF","RxRetryDelay");
	$data = getStrLine($data,"RCF","StationShortName");
	$data = getTripleLine($data,"RCF","ReceiverPosition");
	
	$data = getIntLine($data,"GRCSM","SeverityFilter");
	
	$data = getIntLine($data,"GRDSM","SeverityFilter");
	$data = getIntLine($data,"GRDSM","SampleRate");
	
	$data = getIntLine($data,"GESM","SeverityFilter");
	
	$data = getIntLine($data,"ICM","SeverityFilter");
	$data = getIpLine($data,"ICM","TxIP_Address");
	$data = getIntLine($data,"ICM","TxPortNo");
	$data = getIntLine($data,"ICM","TxSocketType");
	$data = getIntLine($data,"ICM","TxIOTimeout");
	$data = getIntLine($data,"ICM","TxConnectionTimeout");
	$data = getIntLine($data,"ICM","TxRetryDelay");
	
	$data = getIntLine($data,"Processing","SeverityFilter");
	$data = getFloatLine($data,"Processing","DopplerTolerance");
	$data = getFloatLine($data,"Processing","FilterFreq");
	
	$data = getIntLine($data,"Output","SeverityFilter");
	$data = getStrLine($data,"Output","RootDirectory");
	
	return $data;
}

function getIntLine($data,$section,$type) {
	global $fp, $errorMsg;
	
	$value = commonChecks($fp, $type);
	if($value === false) {
		return false;
	}
	
	if(!ctype_digit($value)) {   	//if value not an int
		$errorMsg = "Error when parsing for $type: " . ($value != "" ? $value : "NULL") . " is not a valid INT." ;
		return false;
	}
	
	$data[$section.$type] = $value; //everything is fine, then.
	return $data;
}

function getIpLine($data,$section,$type) {
	global $fp, $errorMsg;
	
	$value = commonChecks($fp, $type);
	if($value === false){
		return false;
	}
	
	//checking if IP is valid: it must be four int, between 0 and 255, separated by dots
	$isOk = 0;
	$ipParts = explode(".",$value);
	for($i=0; $i<count($ipParts); $i++) {
		$currentPart = $ipParts[$i];
		if(ctype_digit($currentPart) && $currentPart >= 0 && $currentPart <= 255) {
			$isOk++;
		}
	}
	
	if($isOk == 4 && !isset($ipParts[4])) { //first part also checks for too long ip (2.5.4.5.1), second for valid ip with stuff behind (3.5.4.2.yoghurt)
		$data[$section.$type] = $value; 	//everything is fine, then.
		return $data;
	} else {
		$errorMsg = "Error when parsing for $type: " . ($value != "" ? $value : "NULL") . " is not a valid IP.";
		return false;
	}
}

function getFloatLine($data,$section,$type) {
	global $fp, $errorMsg;
	
	$value = commonChecks($fp, $type);
	if($value === false) {
		return false;
	}
	
	if(!is_numeric($value)) {   	//if value not in the right format
		$errorMsg = "Error when parsing for $type: " . ($value != "" ? $value : "NULL")  . " is not a valid FLOAT.";
		return false;
	}
	
	$data[$section.$type] = $value; //everything is fine, then.
	return $data;
}

function getTripleLine($data,$section,$type) {
	global $fp, $errorMsg;
	
	$value = commonChecks($fp, $type);
	if($value === false) {
		return false;
	}
	
	//checking that we have at least three floats
	$value = explode(",",$value);
	if(!isset($value[2])) {
		$errorMsg = "Error when parsing for $type: at least one coordinate is missing.";
		return false;
	}
	
	//checking that we have no more than three floats
	if(isset($value[3])) {
		$errorMsg = "Error when parsing for $type: too many coordinates.";
		return false;
	}
	
	for($i=0; $i<3; $i++) {
		if(!is_numeric($value[$i])) {   	//if value not in the right format
			$errorMsg = "Error when parsing for $type: " . ($value[$i] != "" ? $value[$i] : "NULL")  . " is not a valid FLOAT.";
			return false;
		}
	}
	
	//everything is fine, then.
	$data[$section.$type."X"] = $value[0];
	$data[$section.$type."Y"] = $value[1];
	$data[$section.$type."Z"] = $value[2];
	return $data;
}

function getStrLine($data,$section,$type) {
	global $fp, $errorMsg;
	//list of allowed characters
	static $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZÆØŒÅÉÈÊÓÒÂÔæøœåéèêóòâô1234567890-_./";
	
	$value = commonChecks($fp, $type);
	if($value === false) {
		return false;
	}
	
	if(strlen($value) != strspn($value, $charset)){ //if contains other characters than the allowed ones
		$errorMsg = "Error when parsing for $type: unauthorised characters found in $value.";
		return false;
	}	
	
	$data[$section.$type] = $value; //everything is fine, then.
	return $data;
}

function commonChecks($fp, $type) {
	global $errorMsg; 
	
	if($errorMsg) { //if an error has already occurred somewhere before
		return false;
	}
	
	$line = skipUseless($fp);
	if(!$line) {
		$errorMsg = "Error when parsing for $type: possibly an unexpected EOF";
		return false;
	}

	$tmp = explode("=",explode(";",$line)[0]); //removing comments, and splitting along the =
	
	if(!isset($tmp[0])) {  		//if parameter does not exist
		$errorMsg = "Error when parsing for $type: " . ($line != "" ? $line : "empty line") . " found.";
		return false;
	}
	
	if($tmp[0] != $type) { 		//if parameter does not correspond
		$errorMsg = "Error when parsing for $type: " . ($tmp[0] != "" ? $tmp[0] : "NULL") . " found.";
		return false;
	}
	
	if(!isset($tmp[1])) { 		//if value does not exist
		$errorMsg = "Error when parsing for $type: no value found.";
		return false;
	}
	
	if(isset($tmp[2])) { 		//if a line with several equals like "Output=8=20"
		$errorMsg = "Error when parsing for $type: too many equal signs.";
		return false;
	}
		
	return removeEOL($tmp[1]); 	//removing line feeds and semicolons before returning
}

function skipUseless($fp) {
	//skipping comments
	$line = fgets($fp);
	while (substr($line, 0, 1) === ";" || substr($line, 0, 1) === "[") {
		$line = fgets($fp);
		if(!$line) {
			return false;
		}
	}
	return $line;
}

//removing line feeds, end of line tabs and spaces, and semicolons
function removeEOL($string) {
	static $toRemove = array("\t\n","\t\n"," \n"," \r","\n","\r",";");
	
	foreach($toRemove as $eol) {
		$string = str_replace($eol,"",$string);
	}
	
	return $string;
}
?>
