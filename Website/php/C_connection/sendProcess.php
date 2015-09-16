<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include("commandSenderData.php");


while(true) {
	$now = time();
	$returnValue = 1;
	while($returnValue != 0) {
		if(time() - $now > $timeout){
			//echo "data: TIMEOUT: NO ANSWER RECEIVED FROM STATION WITHIN ".$timeout." SECONDS\n\n";
			ob_flush();
			flush();
		}
		//exec("cd ../../C && ./commandSenderData ".$_GET["sta"]." 0",$output,$returnValue);
	}
	
	$fileContents = file_get_contents("./process.txt");
	//$time = date('r');
	//$newData = rand(0, 1000);
	if($fileContents){
		echo "data: Contents of the file: ".$fileContents."\n\n";
		//echo "data: <tr><td>Server time:</td><td>".$time."</td></tr><tr><td>Random number</td><td>".$newData."</td></tr>\n\n"; //$newData
		ob_flush();
		flush();
		sleep($procFreq);
	} else {
		echo "data: No data received yet.\n\n";
		ob_flush();
		flush();
	}
}

?>
