<?php
$timeout = 10;

$procFreq = $_GET["proc"];
if(!ctype_digit($procFreq) || !$procFreq) {
	while(true) {
		echo "data: INVALID PROCESS REFRESH FREQUENCY\n\n";
		ob_flush();
		flush();
		sleep(100);
	}
}

$monFreq = $_GET["mon"];
if(!ctype_digit($monFreq) || !$monFreq) {
	while(true) {
		echo "data: INVALID MONITORING REFRESH FREQUENCY\n\n";
		ob_flush();
		flush();
		sleep(100);
	}
}

$possibleSeverities = [0,1,3,7,15, 16,17,19,23,31, 128,129,131,135,143, 144,145,147,151,159];

$errFilter = $_GET["err"];
if(! in_array($errFilter,$possibleSeverities)) {
	while(true) {
		echo "data: INVALID SEVERITY FILTER FOR ERROR MESSAGES\n\n";
		ob_flush();
		flush();
		sleep(100);
	}
}
?>
