<?php

//connection to the database
function dbConnect() {
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "myDB";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		echo('
			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-dismissable alert-danger">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<h1><strong>Unable to connect to the database: '.$conn->connect_error.'</strong></h1><br/>
						<p class="lead">You may still be able to send configuration files to the station, but the database will not
						keep track of your changes. That can cause problems next time you connect.</p>
					</div>
				</div>
			</div>');
	}
	
	return $conn;

}

?>
