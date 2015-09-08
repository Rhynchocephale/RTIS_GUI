<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include("commandSenderData.php");

while(true) {
	$time = date('r');
	$newData = rand(0, 1000);
	echo "data: <tr><td>Server time:</td><td>".$time."</td></tr><tr><td>Random number</td><td>".$newData."</td></tr>\n\n";
	ob_flush();
	flush();
	sleep($monFreq);
}
?>
