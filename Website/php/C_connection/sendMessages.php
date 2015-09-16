<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include("commandSenderData.php");

$dataFile = "./process.txt";
$heartbeatFreq = 300;

$correspondanceTable = [1 => ["feed" => "fatal", "icon" => "bomb"],
						3 => ["feed" => "error", "icon" => "thumbs-o-down"],
						7 => ["feed" => "warning", "icon" => "exclamation"],
						15 => ["feed" => "info", "icon" => "check"],
						16 => ["feed" => "notice", "icon" => "comments"],
						128 => ["feed" => "debug", "icon" => "comment"]];
						
/*$lastModifTime = filemtime($dataFile);
$md5 = md5_file($dataFile);

while(true) {
	$now = time();
	
		exec("cd ../../C && ./commandSenderData ".$_GET["sta"]." 4",$output,$returnValue);
	
	//checks date of last modif
	$lastModifTime2 = filemtime($dataFile);
	if($lastModifTime != $lastModifTime2){
		//updates date of last modif
		$lastModifTime = $lastModifTime2;
		
		//checks that file really has been changed
		$md5_now = md5_file($dataFile);
		if($md5 != $md5_now){
			$md5 = $md5_now;
		
			$fileContents = file_get_contents($dataFile);


			//TODO: SEVERITY CHECK
			echo "data: <section class=\"feed-item feed-".$correspondanceTable[$message["class"]]["feed"]."\"><div class=\"feed-item-body\"><div class=\"icon pull-left\"><i class=\"fa fa-".$correspondanceTable[$message["class"]]["icon"]."\"></i></div><div class=\"text\">&nbsp;".$message["content"]."</div><div class=\"time pull-left\">".$message["date"]."</div></div></section>\n\n";
			ob_flush();
			flush();
			sleep($procFreq);
		}
	} else {
		sleep(0.1);
	}
}
/*

/*
 *****************************
 * RANDOM MESSAGES GENERATOR *
 ***************************** 
*/
$messageClasses = [1,3,7,15,16,128];

$debug = false;
$notice = false;
if($errFilter >= 128) {
	$debug = true;
	$errFilter -= 128;
}
if($errFilter >= 16) {
	$notice = true;
	$errFilter -= 16;
}


$correspondanceTable = [1 => ["feed" => "fatal", "icon" => "bomb"],
						3 => ["feed" => "error", "icon" => "thumbs-o-down"],
						7 => ["feed" => "warning", "icon" => "exclamation"],
						15 => ["feed" => "info", "icon" => "check"],
						16 => ["feed" => "notice", "icon" => "comments"],
						128 => ["feed" => "debug", "icon" => "comment"]];

$i = 0;
while(true) {
	//messages generator and sender
	$randomNumber = rand(0, 100000);
	//$randomNumber = 2;
	if($randomNumber == 2) {
		
		$message = ["content" => substr(MD5(microtime()), 0, 10), 						//random string
					"class" => $messageClasses[rand(0, count($messageClasses)-1)], //$messageClasses[$i],		//random array element
					"date" => timeAndMilliseconds()];
		
		//sending messages
		if( ($errFilter >= $message["class"]) || ($debug && $message["class"] == 128) || ($notice && $message["class"] == 16) ) {
			echo "data: <section class=\"feed-item feed-".$correspondanceTable[$message["class"]]["feed"]."\"><div class=\"feed-item-body\"><div class=\"icon pull-left\"><i class=\"fa fa-".$correspondanceTable[$message["class"]]["icon"]."\"></i></div><div class=\"text\">&nbsp;".$message["content"]."</div><div class=\"time pull-left\">".$message["date"]."</div></div></section>\n\n";
			ob_flush();
			flush();
		}
	}
	//sleep(2);
}

function timeAndMilliseconds()
{
    $m = explode(' ',microtime());
    return date("d-m-Y H:i:s.", $m[1]) . (int)round($m[0]*1000,3);
}

?>
