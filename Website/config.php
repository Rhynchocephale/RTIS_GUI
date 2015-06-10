<?php
	include("head.php"); 
	include("database.php");

	$listOfFields = ["fileName", "station", "RCFSeverityFilter", "RCFRxIP_Address", "RCFRxPortNo", "RCFRxSocketType", "RCFRxIOTimeout", "RCFRxConnectionTimeout", "RCFRxRetryDelay", "RCFStationShortName", "RCFReceiverPositionX", "RCFReceiverPositionY", "RCFReceiverPositionZ", "GRCSMSeverityFilter", "GRDSMSeverityFilter", "GRDSMSampleRate", "GESMSeverityFilter", "ICMSeverityFilter", "ICMTxIP_Address", "ICMTxPortNo", "ICMTxSocketType", "ICMTxIOTimeout", "ICMTxConnectionTimeout", "ICMTxRetryDelay", "ProcessingSeverityFilter", "ProcessingDopplerTolerance", "ProcessingFilterFreq", "OutputSeverityFilter", "OutputRootDirectory"];
?> 

<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-dismissable alert-warning">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			The changes you make here will not take effect until the next reboot.
		</div>
	</div>
</div>

<div class="panel panel-primary">
	<div class="panel-heading">
		  <h3 class="panel-title">Load a file</h3>
	</div>
	<div class="panel-body">
		<form role="form" name="fileUpload" id="fileUpload">

			<div class="col-lg-12">
				<div class="form-group">
					<label>Select your file</label>
					<input type="file">
				</div>
			</div>
			
			<input type="hidden" name="saveInDb" value="true">

			<div class="pull-right">
				<button name="saveSendFileButton" type="button" class="btn btn-success" onclick="checkAndSubmitFile('both');">Save & send</button>
				<button name="saveFileButton" type="button" class="btn btn-primary" onclick="checkAndSubmitFile('save');">Save</button>
				<button name="sendFileButton" type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmNoSaveFile">Send (but don't save)</button>
			</div>

			<!-- Send (no save) -->
			<div class="modal fade" id="confirmNoSaveFile" tabindex="-1" role="dialog" aria-labelledby="confirmNoSaveFile" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h2 class="modal-title">Are you sure you want to do this?</h2>
						</div>
						<div class="modal-body">
							<h3>This new file will be sent to the server, but will not be saved in the database.
							The current active file will still be considered as active by this interface only.
							This means the current file will still display next time, even if your new file really
							is the active one.
							</h3>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="checkAndSubmitFile('send');">I know what I'm doing.</button>
							<button type="button" class="btn btn-success" data-dismiss="modal">I changed my mind.</button>
						</div>
					</div>
				</div>
			</div>

		</form>
	</div>
</div>

<div class="panel panel-primary">

	<div class="panel-heading">
		  <h3 class="panel-title">Make your own configuration file</h3>
	</div>
	<div class="panel-body">
		<form role="form" name="filesDropdown" id="filesDropdown">
			<div class="form-group">
				<label>Choose your model</label>
				<select id="selectConfig" name="selectConfig" class="form-control" onchange="prefillFields();" required>
					<option value="-1">No file found</option>
				</select>
				<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirmDelete"><i class="fa fa-trash"></i> Irreversibly delete this file</button>
			</div>
			<br/>

			<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="confirmDelete" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h2 class="modal-title">Are you sure you want to do this?</h2>
						</div>
						<div class="modal-body">
							<h3>This file will be deleted from the database only, with no possible recovery.
							Please note that this will not affect the remote station at all.
							Do you really want to continue?</h3>
						</div>
						<div class="modal-footer">
							<button type="button" name="deleteButton" class="btn btn-danger" data-dismiss="modal" onclick="deleteFileFromDb();">Yep, sure.</button>
							<button type="button" class="btn btn-success" data-dismiss="modal">Well, not really.</button>
						</div>
					</div>
				</div>
			</div>
		</form>

		<form role="form" name="manualConfigForm" id="manualConfigForm">
			<div class="form-group">
				<label>Name of the new file (optional)</label>
				<input name="fileName" class="form-control optional">
			</div>

			<input type="hidden" name="station" value="<?php echo $_GET["sta"] ?>">

			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">RTIM Configuration file</div>
					<div class="panel-footer">

						<div class="form-group">
							<label>SeverityFilter</label>
							<select name="RCFSeverityFilter" class="form-control">
								<option value="0">No error message</option>
								<option value="1">Fatal</option>
								<option value="3">Error</option>
								<option value="7">Warning</option>
								<option value="15">Info</option>
								<option value="31" selected="selected">All</option>
								<option value="159">Debug</option>
							</select>
						</div>

						<div class="form-group">
							<label>RxIP_Address</label>
							<input name="RCFRxIP_Address" class="form-control" onchange="validateIp('RCFRxIP_Address');" required>
						</div>

						<div class="form-group">
							<label>RxPortNo</label>
							<input name="RCFRxPortNo" class="form-control" onchange="validateUint('RCFRxPortNo');" required>
						</div>

						<div class="form-group">
							<label>RxSocketType</label>
							<input name="RCFRxSocketType" class="form-control" onchange="validateUint('RCFRxSocketType');" required>
						</div>

						<label>RxIOTimeout</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="RCFRxIOTimeout" class="form-control" onchange="validateUint('RCFRxIOTimeout');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>

						<label>RxConnectionTimeout</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="RCFRxConnectionTimeout" class="form-control" onchange="validateUint('RCFRxConnectionTimeout');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>

						<label>RxRetryDelay</label>
						<div class="form-group input-group">
							<div class="input-group">
								<input type="text" name="RCFRxRetryDelay" class="form-control" onchange="validateUint('RCFRxRetryDelay');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>

						<div class="form-group">
							<label>StationShortName</label>
							<input name="RCFStationShortName" class="form-control" required>
						</div>

						<label>ReceiverPosition</label>
						<div class="form-group">
							<div class="input-group">
								<input name="RCFReceiverPositionX" class="form-control" placeholder="X" onchange="validateFloat('RCFReceiverPositionX');" required>
								<input name="RCFReceiverPositionY" class="form-control" placeholder="Y" onchange="validateFloat('RCFReceiverPositionY');" required>
								<input name="RCFReceiverPositionZ" class="form-control" placeholder="Z" onchange="validateFloat('RCFReceiverPositionZ');" required>
								<span class="input-group-addon">m</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">GNSS Receiver Command Server Module</div>
					<div class="panel-footer">
						<div class="form-group">
							<label>SeverityFilter</label>
							<select name="GRCSMSeverityFilter" class="form-control">
								<option value="0">No error message</option>
								<option value="1">Fatal</option>
								<option value="3">Error</option>
								<option value="7">Warning</option>
								<option value="15">Info</option>
								<option value="31" selected="selected">All</option>
								<option value="159">Debug</option>
							</select>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">GNSS RawData Server Module</div>
					<div class="panel-footer">
						<div class="form-group">
							<label>SeverityFilter</label>
							<select name="GRDSMSeverityFilter" class="form-control">
								<option value="0">No error message</option>
								<option value="1">Fatal</option>
								<option value="3">Error</option>
								<option value="7">Warning</option>
								<option value="15">Info</option>
								<option value="31" selected="selected">All</option>
								<option value="159">Debug</option>
							</select>
						</div>

						<label>SampleRate</label>
						<div class="form-group input-group">
							<select name="GRDSMSampleRate" class="form-control">
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="40">40</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="200">200</option>
								<option value="500">500</option>
								<option value="1000">1000</option>
							</select>
							<span class="input-group-addon">msec</span>
						</div>

					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">GNSS Ephemerides Server Module</div>
					<div class="panel-footer">
						<div class="form-group">
							<label>SeverityFilter</label>
							<select name="GESMSeverityFilter" class="form-control">
								<option value="0">No error message</option>
								<option value="1">Fatal</option>
								<option value="3">Error</option>
								<option value="7">Warning</option>
								<option value="15">Info</option>
								<option value="31" selected="selected">All</option>
								<option value="159">Debug</option>
							</select>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Processing</div>
					<div class="panel-footer">

						<div class="form-group">
							<label>SeverityFilter</label>
							<select name="ProcessingSeverityFilter" class="form-control">
								<option value="0">No error message</option>
								<option value="1">Fatal</option>
								<option value="3">Error</option>
								<option value="7">Warning</option>
								<option value="15">Info</option>
								<option value="31" selected="selected">All</option>
								<option value="159">Debug</option>
							</select>
						</div>

						<div class="form-group">
							<label>DopplerTolerance</label>
							<input name="ProcessingDopplerTolerance" class="form-control" onchange="validateFloat('ProcessingDopplerTolerance');" required>
						</div>

						<div class="form-group">
							<label>FilterFreq</label>
							<input name="ProcessingFilterFreq" class="form-control" onchange="validateFloat('ProcessingFilterFreq');" required>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">Index Client Module</div>
					<div class="panel-footer">

						<div class="form-group">
							<label>SeverityFilter</label>
							<select name="ICMSeverityFilter" class="form-control">
								<option value="0">No error message</option>
								<option value="1">Fatal</option>
								<option value="3">Error</option>
								<option value="7">Warning</option>
								<option value="15">Info</option>
								<option value="31" selected="selected">All</option>
								<option value="159">Debug</option>
							</select>
						</div>

						<div class="form-group">
							<label>TxIP_Address</label>
							<input name="ICMTxIP_Address" class="form-control" onchange="validateIp('ICMTxIP_Address');" required>
						</div>

						<div class="form-group">
							<label>TxPortNo</label>
							<input name="ICMTxPortNo" class="form-control" onchange="validateUint('ICMTxPortNo');" required>
						</div>

						<div class="form-group">
							<label>TxSocketType</label>
							<input name="ICMTxSocketType" class="form-control" onchange="validateUint('ICMTxSocketType');" required>
						</div>

						<label>TxIOTimeout</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="ICMTxIOTimeout" class="form-control" onchange="validateUint('ICMTxIOTimeout');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>

						<label>TxConnectionTimeout</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="ICMTxConnectionTimeout" class="form-control" onchange="validateUint('ICMTxConnectionTimeout');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>

						<label>TxRetryDelay</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="ICMTxRetryDelay" class="form-control" onchange="validateUint('ICMTxRetryDelay');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Output</div>
					<div class="panel-footer">

						<div class="form-group">
							<label>SeverityFilter</label>
							<select name="OutputSeverityFilter" class="form-control">
								<option value="0">No error message</option>
								<option value="1">Fatal</option>
								<option value="3">Error</option>
								<option value="7">Warning</option>
								<option value="15">Info</option>
								<option value="31" selected="selected">All</option>
								<option value="159">Debug</option>
							</select>
						</div>

						<div class="form-group">
							<label>RootDirectory</label>
							<input name="OutputRootDirectory" class="form-control" onchange="validatePath('OutputRootDirectory');" required>
						</div>
					</div>
				</div>
			</div>

			<div class="pull-right">
				<button name="saveSendButton" type="button" class="btn btn-success" onclick="checkAndSubmit('both');">Save & send</button>
				<button name="saveButton" type="button" class="btn btn-primary" onclick="checkAndSubmit('save');">Save</button>
				<button name="sendButton" type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmNoSave">Send (but don't save)</button>
			</div>

			<!-- Send (no save) -->
			<div class="modal fade" id="confirmNoSave" tabindex="-1" role="dialog" aria-labelledby="confirmNoSave" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h2 class="modal-title">Are you sure you want to do this?</h2>
						</div>
						<div class="modal-body">
							<h3>This new file will be sent to the server, but will not be saved in the database.
							The current active file will still be considered as active by this interface only.
							This means the current file will still display next time, even if your new file really
							is the active one.
							</h3>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="checkAndSubmit('send');">I know what I'm doing.</button>
							<button type="button" class="btn btn-success" data-dismiss="modal">I changed my mind.</button>
						</div>
					</div>
				</div>
			</div>

		</form>
	</div> <!-- End of panel-body -->
</div>

<script>
	setActive("li-config");
	getList();

	function getList() {
		$.ajax({
			url: "getDropdownListOfConfigFiles.php?sta=<?php echo $_GET["sta"] ?>",
			type: "GET",
			data: $(this).serialize(), //sending all values at once
			success: function(data) {
				$('#selectConfig').html(data);
			}, 
			complete : function(result, status){
				prefillFields();
			}
		});
	}

	function prefillFields() {
		var selectedOption = $("#selectConfig").val();
		if(selectedOption != "-1") { //if file found
			$.ajax({
				url: 'prefillFields.php',
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
	
	function deleteFileFromDb() {
		var selectedOption = $("#selectConfig").val();
		if(selectedOption != "-1") { //if file found
			$.ajax({
				url: 'deleteFileFromDb.php',
				type: 'GET',
				data: 'selectedOption='+selectedOption,
				complete : function(result, status){
					getList();
				}
			});
		}
	}

	function checkAndSubmitFile(action) {
		$this = $("#uploadFile");
		$.ajax({
			url: 'parseFile.php',
			type: 'POST',
			data: $this.serialize(),    //sending all values at once
			success: function(answer) { //getting answer from php
				if(answer == "Success") {
					location.reload(true); //reload page 
				} else {
					alert(answer); // printing error
				}
			}
		});
			
		if(action == "save" || action == "both") {
			$this = $("#manualConfigForm");
			$.ajax({
				url: 'saveConfigInDb.php',
				type: 'GET',
				data: $this.serialize(),    //sending all values at once
				success: function(answer) { //getting answer from php
					if(answer == "Success") {
						location.reload(true); //reload page 
					} else {
						alert(answer); // printing error
					}
				}
			});
		}
		
		if(action == "send" || action == "both") {
			//TO DO
		}
	}
	
	function checkAndSubmit(action) {
		if (!checkAllValid()){
			return false;
		}
		
		if(action == "save") {
			$this = $("#manualConfigForm");
			$.ajax({
				url: 'saveConfigInDb.php',
				type: 'GET',
				data: $this.serialize(),    //sending all values at once
				success: function(answer) { //getting answer from php
					if(answer == "Success") {
						location.reload(true); //reload page 
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
	
</script>
<script type="text/javascript" src="js/validateConfig.js"></script>
<?php include("foot.php"); ?>
