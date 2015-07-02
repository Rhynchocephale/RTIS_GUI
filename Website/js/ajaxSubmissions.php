//gets the dropdown list of entries in the database
function getList() {
	$.ajax({
		url: "../php/Database/getDropdownListOfConfigFiles.php?sta=<?php echo $_GET["sta"] ?>",
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
			url: '../php/Database/prefillFields.php',
			type: 'GET',
			data: 'selectedOption='+selectedOption,
			dataType: 'json',
			success: function(data) {
				var listOfFields = ["station", "fileName", "RCFSeverityFilter", "RCFRxIP_Address", "RCFRxPortNo", "RCFRxSocketType", "RCFRxIOTimeout", "RCFRxConnectionTimeout", "RCFRxRetryDelay", "RCFStationShortName", "RCFReceiverPositionX", "RCFReceiverPositionY", "RCFReceiverPositionZ", "GRCSMSeverityFilter", "GRDSMSeverityFilter", "GRDSMSampleRate", "GESMSeverityFilter", "ICMSeverityFilter", "ICMTxIP_Address", "ICMTxPortNo", "ICMTxSocketType", "ICMTxIOTimeout", "ICMTxConnectionTimeout", "ICMTxRetryDelay", "ProcessingSeverityFilter", "ProcessingDopplerTolerance", "ProcessingFilterFreq", "OutputSeverityFilter", "OutputRootDirectory"];
				var severityFiltersFull = ["RCFSeverityFilter","GRCSMSeverityFilter","GRDSMSeverityFilter","GESMSeverityFilter","ICMSeverityFilter","ProcessingSeverityFilter","OutputSeverityFilter"];
				var i;
				var tmpVal;
				
				//filling each value
				for (i = 0; i < listOfFields.length; i++) {
					var element = listOfFields[i];
					if(element != "station") {
						
						//special case for the severity filters, as we'll have to check if notice or debug
						if(severityFiltersFull.indexOf(element) != -1) {
							tmpVal = +decodeURI(data[element]);
							if(tmpVal >= 128) {
								tmpVal -= 128;
								$("[name=" + element + "Debug]").prop("checked", true);
							} else {
								$("[name=" + element + "Debug]").prop("checked", false);
							}
							if(tmpVal >= 16) {
								tmpVal -= 16;
								$("[name=" + element + "Notice]").prop("checked", true);
							} else {
								$("[name=" + element + "Notice]").prop("checked", false);
							}
							$("[name=" + element + "]").val(tmpVal);
						} else {
							$("[name=" + element + "]").val(decodeURI(data[element]));
						}
					}
				}
			}
		});
	}
}

//removes the currently selected file from the database
function deleteFileFromDb() {
	var selectedOption = $("#selectConfig").val();
	if(selectedOption != "-1") { //if file found
		$.ajax({
			url: '../php/Database/deleteFileFromDb.php',
			type: 'GET',
			data: 'selectedOption='+selectedOption,
			complete : function(result, status){
				getList();
			}
		});
	}
}

//if user wants to upload its own file, checks that the file is correctly formatted, and adds it to the database
//possible values for action: 0 save only, 1 save & send, 2 send only
function checkAndSubmitFile(action) {
	// Create a formdata object and add the file
	var stuffToSend = new FormData(document.forms.namedItem("fileUpload"));
	
	$.ajax({
		url: '../php/Database/parseFile.php',
		type: 'POST',
		data: stuffToSend, 	  					// Create a formdata object and add the files
		contentType: false,       				// The content type used when sending data to the server.
		cache: false,             				// To unable request pages to be cached
		processData:false,        				// To send DOMDocument or non processed data file it is set to false
		success: function(answer, status) { 	// To get an answer from php
			if(answer.charAt(0) === "{") { 		//if answer is a json string
				if(action == 2) { 				//save only
					submitJson(answer,false); 	//saving in the db
				} else if(action == 1) {		//save&send
					submitJson(answer,true); 	//saving in the db
				}
			} else {
				alert(answer);
			}
		}
	});
		
	//saves the parsed config file in the db
	function submitJson(jsonString,isActive) {
		dataToSend = jsonString2getString(jsonString);							  //converting to a "url" format
		dataToSend += "&active="+isActive+"&fileName=uploadedFile&station=<?php echo $_GET["sta"] ?>"; //adding missing information
		
		$.ajax({
			url: '../php/Database/saveConfigInDb.php',
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
//possible values for action: 0 save only, 1 save & send, 2 send only
function checkAndSubmit(action) {
	if (!checkAllValid()){				//if any wrongly filled field
		return false;
	}
	
	var failureMessage = "";
	$this = $("#manualConfigForm").serialize();
	
	if(action < 2) { 										//save
		$.ajax({
			url: '../php/Database/saveConfigInDb.php',
			type: 'GET',
			data: $this + "&active=" + (action==0?"false":"true"),		//sending all values at once, and setting active attribute
			success: function(answer) { 					//getting answer from php
				if(answer != "Success") {
					failureMessage += answer;
				}
			}
		});
	}
	
	
	if(action > 0) {
		$.ajax({
			url: '../php/C_connection/sendConfig.php',
			type: 'POST',
			data: "array="+$this.split("&").join(","),		//sending all values at once & replacing "&" with "," to avoid weird behaviours
			success: function(answer) { 					//getting answer from php
				/*if(answer != "Success") {
					failureMessage += answer;
				}*/
				alert(answer);
			}
		});
	}
	
	/*if(!failureMessage) {						//if no error, then success
		location.reload(true); 					//reload page 
	} else {
		alert(failureMessage);					//prints error message
	}*/
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

