//gets the dropdown list of entries in the database
function getList() {
	$.ajax({
		url: "../php/getDropdownListOfConfigFiles.php?sta=<?php echo $_GET["sta"] ?>",
		type: "GET",
		success: function(data) {
			$('#selectConfig').html(data);
		}, 
		complete : function(result, status){
			prefillFields();
		}
	});
}

//queries the database and prefills all the input fields with the values for the currently selected model file
function prefillFields() {
	var selectedOption = $("#selectConfig").val();
	if(selectedOption != "-1") { //if file found
		$.ajax({
			url: '../php/prefillFields.php',
			type: 'GET',
			data: 'selectedOption='+selectedOption,
			dataType: 'json',
			success: function(data) {
				<?php 
					foreach($listOfFields as $field) { //filling each value
						if($field != "station") {
							echo "\t\t\t\t\t$(\"[name=".$field."]\").val(decodeURI(data[\"".$field."\"]));\n";
						}
					}
				?>
			}
		});
	}
}

//removes the currently selected file from the database
function deleteFileFromDb() {
	var selectedOption = $("#selectConfig").val();
	if(selectedOption != "-1") { //if file found
		$.ajax({
			url: '../php/deleteFileFromDb.php',
			type: 'GET',
			data: 'selectedOption='+selectedOption,
			complete : function(result, status){
				getList();
			}
		});
	}
}

//if user wants to upload its own file, checks that the file is correctly formatted, and adds it to the database
function checkAndSubmitFile(action) {
	// Create a formdata object and add the file
	var stuffToSend = new FormData(document.forms.namedItem("fileUpload"));
	
	$.ajax({
		url: '../php/parseFile.php',
		type: 'POST',
		data: stuffToSend, 	  					// Create a formdata object and add the files
		contentType: false,       				// The content type used when sending data to the server.
		cache: false,             				// To unable request pages to be cached
		processData:false,        				// To send DOMDocument or non processed data file it is set to false
		success: function(answer, status) { 	// To get an answer from php
			if(answer.charAt(0) === "{") { 		//if answer is a json string
				if(action == "save" || action == "both") {
					submitJson(answer); 		//saving in the db
				}
			} else {
				alert(answer);
			}
		}
	});
		
	//saves the parsed config file in the db
	function submitJson(jsonString) {
		dataToSend = jsonString2getString(jsonString);							  //converting to a "url" format
		dataToSend += "fileName=uploadedFile&station=<?php echo $_GET["sta"] ?>"; //adding missing information
		
		$.ajax({
			url: '../php/saveConfigInDb.php',
			type: 'GET',
			data: dataToSend,				//sending all values at once
			success: function(answer) { 	//getting answer from php
				if(answer == "Success") {
					location.reload(true); 	//reload page 
				} else {
					alert(answer); 			//printing error
				}
			}
		});
	}
}

//saving in database for forms manually filled in by user
function checkAndSubmit(action) {
	if (!checkAllValid()){				//if any wrongly filled field
		return false;
	}
	
	if(action == "save") {
		$this = $("#manualConfigForm");
		$.ajax({
			url: '../php/saveConfigInDb.php',
			type: 'GET',
			data: $this.serialize(),		//sending all values at once
			success: function(answer) { 	//getting answer from php
				if(answer == "Success") {
					location.reload(true); 	//reload page 
				} else {
					alert(answer); // printing error
				}
			}
		});
	}
	
	if(action == "send") {
		//TO DO
	}
}

//converting a json string to a "url" format
function jsonString2getString(jsonString) {
	var getString = "";
	var splitString = jsonString.replace("{","").replace("}","").split(",");
	
	for (var i = 0; i < splitString.length; i++) {
		getString += splitString[i].replace(/\"/g,"").replace(":","=")+"&";
	}
	return getString;
}

