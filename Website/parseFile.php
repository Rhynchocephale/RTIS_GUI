<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
$errorMsg = "";

// Check file size (no more than 3kB)
if ($_FILES["fileToUpload"]["size"] > 3000) {
	$errorMsg .= "Sorry, your file is too large.";
	$uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "cfg") {
	$errorMsg .= "Sorry, only .cfg files are allowed.";
	$uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
	echo $errorMsg;
	
// if everything is ok, read file
} else {
	$fp = fopen($_FILES['uploadFile']['tmp_name'], 'rb');
	parseValues($fp);
}


function parseValues($fp){
	//skipping comments
	while (substr($fgets($fp), 0, 1) === ";") {
	}
	$fgets($fp); //skipping [MAIN];
	$parseError = "";
	$parseError .= getIntLine("RCFSeverityFilter");
	$parseError .= getIntLine("RCFStationId");
	$parseError .= getIpLine("RCFRxIP_Address");
	$parseError .= getIntLine("RCFRxPortNo");
	$parseError .= getIntLine("RCFRxSocketType");
	$parseError .= getIntLine("RCFRxIOTimeout");
	
}

function getIntLine($key) {
	global $data, $fp;
	$data[$key] = explode($fgets($fp),"=")[1];
	if(!is_int($data[$key])) {
		return $key . " is not a valid INT.\n";
	} else {
		return "";
	}
}

function getIpLine($key) {
	global $data;
	$data[$key] = explode($fgets($fp),"=")[1];
	$ipParts = explode($data[$key],".");
	$isOk = 0;
	for($i=0; $i<4; i++) {
		if(isset($ipParts[$i])) {
			$currentPart = $ipParts[$i];
			if(is_int($currentPart) && $currentPart >= 0 && $currentPart <= 255)
				$isOk++;
			}
		}
	}
	if($isOk == 4) {
		return "";
	} else {
		return $key . " is not a valid INT.\n";
	}
}
?>
