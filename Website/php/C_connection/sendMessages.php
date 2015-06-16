<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$possibleSeverities = [0,1,3,7,15, 16,17,19,23,31, 128,129,131,135,143, 144,145,147,151,159];

$filter = $_GET["param"];
if(! in_array($filter,$possibleSeverities)) {
	while(true) {
		echo "data: INVALID SEVERITY FILTER\n\n";
		ob_flush();
		flush();
		sleep(100);
	}
}

$messageClasses = [1,3,7,15,16,128];

$debug = false;
$notice = false;
if($filter >= 128) {
	$debug = true;
	$filter -= 128;
}
if($filter >= 16) {
	$notice = true;
	$filter -= 16;
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
	$randomNumber = rand(0, 1000000);
	if($randomNumber < 3) {
		
		$message = ["content" => substr(MD5(microtime()), 0, 10), 						//random string
					"class" => $messageClasses[rand(0, count($messageClasses)-1)], //$messageClasses[$i],		//random array element
					"date" => timeAndMilliseconds()];
		
		/*if($i == count($messageClasses) - 1) {
			$i = 0;
		} else {
			$i++;
		}*/
		
		//sending messages
		if( ($filter >= $message["class"]) || ($debug && $message["class"] == 128) || ($notice && $message["class"] == 16) ) {
			echo "data: <section class=\"feed-item feed-".$correspondanceTable[$message["class"]]["feed"]."\"><div class=\"feed-item-body\"><div class=\"icon pull-left\"><i class=\"fa fa-".$correspondanceTable[$message["class"]]["icon"]."\"></i></div><div class=\"text\">&nbsp;".$message["content"]."</div><div class=\"time pull-left\">".$message["date"]."</div></div></section>\n\n";
			ob_flush();
			flush();
		}
	}
}

function timeAndMilliseconds()
{
    $m = explode(' ',microtime());
    return date("d-m-Y H:i:s.", $m[1]) . (int)round($m[0]*1000,3);
}
?>

