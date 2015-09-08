<?php
$timeout = 10;

$toSend = '';
$split = explode(",",$_POST["array"]);
for($i=1; $i<28; $i++) { //skipping fileName, we don't need that
	$toSend .= explode("=",$split[$i])[1]." ";
}
$toSend = implode(" ",$toSend);

$now = time();
$returnValue = 1;
while($returnValue != 0) {
	if(time() - $now > $timeout){
		echo "TIMEOUT: NO ANSWER RECEIVED FROM STATION WITHIN ".$timeout." SECONDS";
		ob_flush();
		flush();
	}
	exec("cd ../../C && ./commandSenderConfig ".$toSend,$output,$returnValue);
	sleep(1);
}
echo "Success";
?>
