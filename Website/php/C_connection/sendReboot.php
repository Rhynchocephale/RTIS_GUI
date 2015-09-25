<?php
$timeout = 15;


$now = time();
$returnValue = 1;
while($returnValue != 0) {
	$elapsedTime = time() - $now;
	if($elapsedTime > $timeout){
		echo "TIMEOUT: NO ANSWER RECEIVED FROM STATION WITHIN ".$elapsedTime." SECONDS. RETRYING.";
		ob_flush();
		flush();
	}
	exec("cd ../../C/Server && ./commandSenderData ".$_GET["sta"]." 3",$output,$returnValue);
	sleep(1);
}
echo "Success";
ob_flush();
flush();
?>
