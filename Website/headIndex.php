<!DOCTYPE html>
<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RTIS GUI</title>
	<link rel="icon" type="image/png" href="resources/icon.ico">

	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="css/local.css" />
	<link rel="stylesheet" type="text/css" href="css/custom.css" />

	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

	<!-- you need to include the shieldui css and js assets in order for the charts to work -->
	<!-- <link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/shieldui-all.min.css" />
	<link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
	<link id="gridcss" rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/dark-bootstrap/all.min.css" /> 

	<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
	<script type="text/javascript" src="http://www.prepbootstrap.com/Content/js/gridData.js"></script> -->
	
	<script type="text/javascript" src="js/custom.js"></script>
</head>
<body>

	<div id="wrapper">

		  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			  
			<div class="navbar-header">
				
				<!-- Mini menu for small screens -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				
				<a class="navbar-brand" href="index.php">RTIS GUI</a>
			</div>
			
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul id="active" class="nav navbar-nav side-nav">
					<li id="sta1"><a href="data.php?sta=1"><i class="fa fa-circle-o station-up"></i> Station 1</a></li>
					<li id="sta2"><a href="data.php?sta=2"><i class="fa fa-circle-o station-down"></i> Station 2</a></li>
					<li id="sta3"><a href="data.php?sta=3"><i class="fa fa-circle-o station-unknown"></i> Station 3</a></li>
					<li id="sta4"><a href="data.php?sta=4"><i class="fa fa-circle-o station-up"></i> Station 4</a></li>
					<li id="sta5"><a href="data.php?sta=5"><i class="fa fa-circle-o station-up"></i> Station 5</a></li>
					<li id="sta6"><a href="data.php?sta=6"><i class="fa fa-circle-o station-up"></i> Station 6</a></li>
					<li id="sta7"><a href="data.php?sta=7"><i class="fa fa-circle-o station-up"></i> Station 7</a></li>
					<li id="sta8"><a href="data.php?sta=8"><i class="fa fa-circle-o station-up"></i> Station 8</a></li>
					<li id="sta9"><a href="data.php?sta=9"><i class="fa fa-circle-o station-up"></i> Station 9</a></li>
					<li id="sta10"><a href="data.php?sta=10"><i class="fa fa-circle-o station-up"></i> Station 10</a></li>
					<li id="sta11"><a href="data.php?sta=11"><i class="fa fa-circle-o station-up"></i> Station 11</a></li>
					<li id="sta12"><a href="data.php?sta=12"><i class="fa fa-circle-o station-up"></i> Station 12</a></li>
				</ul>
			</div>
		</nav>

		<div id="page-wrapper">
			<br/>
