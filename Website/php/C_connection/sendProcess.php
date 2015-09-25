<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include("commandSenderData.php");


while(true) {
	$now = time();
	$returnValue = 1;
	while($returnValue != 0) {
		$elapsedTime = time() - $now;
		if($elapsedTime > $timeout){
			echo "data: TIMEOUT: NO ANSWER RECEIVED FROM STATION WITHIN ".$elapsedTime." SECONDS. RETRYING.\n\n";
			ob_flush();
			flush();
		}
		exec("cd ../../C/Server && ./commandSenderData ".$_GET["sta"]." 0",$output,$returnValue);
		sleep(1);
	}
	
	echo "data: ".$output."\n\n";
	ob_flush();
	flush();
	$remainingSleep = $procFreq - $elapsedTime;
	if($remainingSleep > 0){
		sleep($procFreq);
	}
}

?>
