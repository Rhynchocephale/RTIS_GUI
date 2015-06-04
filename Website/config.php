<!DOCTYPE html>
<?php
	include("head.php"); 
	include("database.php");
	include("getDropdownListOfConfigFiles.php");
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
		<form role="form">
			
			<div class="col-lg-12">
				<div class="form-group">
					<label>Select your file</label>
					<input type="file">
				</div>
			</div>
			
			<div class="pull-right">
				<button name="saveSend" type="button" class="btn btn-success">Save & send</button>
				<button name="save" type="button" class="btn btn-primary">Save</button>
				<button name="send" type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmNoSave">Send (but don't save)</button>
			</div>
			
		</form>
	</div>
</div>

<div class="panel panel-primary">
	
	<div class="panel-heading">
		  <h3 class="panel-title">Make your own configuration file</h3>
	</div>
	<div class="panel-body">
		<form role="form" name="filesDropdown" method="post" action="prefillFields.php">
			<div class="form-group">
				<label>Choose your model</label>
				<select name="selectConfig" class="form-control" onchange="submitForm('filesDropdown', 'prefillFields.php');" required>
					<?php echo $listOfValues; ?>
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
							<h3>This file will be deleted from the database, with no possible recovery. Do you really want to continue?</h3>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="submitForm('filesDropdown', 'deleteFileFromDb.php');">Yep, sure.</button>
							<button type="button" class="btn btn-success" data-dismiss="modal">Well, not really.</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		
		<form role="form" name="manualConfigForm" method="post" action="submitConfig.php">
			<div class="form-group">
				<label>Name of the new file (optionnal)</label>
				<input id="fileName" class="form-control optional">
			</div>
			
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">RTIM Configuration file</div>
					<div class="panel-footer">
				
						<div class="form-group">
							<label>SeverityFilter</label>
							<select name="RCFSeverityFilter"class="form-control">
								<option <?php if ($RCFSeverityFilter == 0) echo 'selected="selected"'; ?> value="0">No error message</option>
                                <option <?php if ($RCFSeverityFilter == 1) echo 'selected="selected"'; ?> value="1">Fatal</option>
                                <option <?php if ($RCFSeverityFilter == 3) echo 'selected="selected"'; ?>value="3">Error</option>
                                <option <?php if ($RCFSeverityFilter == 7) echo 'selected="selected"'; ?>value="7">Warning</option>
                                <option <?php if ($RCFSeverityFilter == 15) echo 'selected="selected"'; ?>value="15">Info</option>
                                <option <?php if ($RCFSeverityFilter == 31 || !isset($RCFSeverityFilter)) echo 'selected="selected"'; ?>value="31" selected="selected">All</option>
                                <option <?php if ($RCFSeverityFilter == 159) echo 'selected="selected"'; ?>value="159">Debug</option>
                            </select>
						</div>
						
						<div class="form-group">
							<label>RxIP_Address</label>
							<input name="RCFRxIP_Address" class="form-control" value="<?php echo $RCFRxIP_Address; ?>" onchange="validateIp('RCFRxIP_Address');" required>
						</div>
						
						<div class="form-group">
							<label>RxPortNo</label>
							<input name="RCFRxPortNo" class="form-control" value="<?php echo $RCFStationId; ?>" onchange="validateUint('RCFRxPortNo');" required>
						</div>
						
						<div class="form-group">
							<label>RxSocketType</label>
							<input name="RCFRxSocketType" class="form-control" value="<?php echo $RCFRxSocketType; ?>" onchange="validateUint('RCFRxSocketType');" required>
						</div>
						
						<label>RxIOTimeout</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="RCFRxIOTimeout" class="form-control" value="<?php echo $RCFRxIOTimeout; ?>" onchange="validateUint('RCFRxIOTimeout');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>
						
						<label>RxConnectionTimeout</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="RCFRxConnectionTimeout" class="form-control" value="<?php echo $RCFRxConnectionTimeout; ?>" onchange="validateUint('RCFRxConnectionTimeout');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>
						
						<label>RxRetryDelay</label>
						<div class="form-group input-group">
							<div class="input-group">
								<input type="text" name="RCFRxRetryDelay" class="form-control" value="<?php echo $RCFRxRetryDelay; ?>" onchange="validateUint('RCFRxRetryDelay');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>
						
						<div class="form-group">
							<label>StationShortName</label>
							<input name="RCFStationShortName" class="form-control" value="<?php echo $RCFStationShortName; ?>">
						</div>
						
						<label>ReceiverPosition</label>
						<div class="form-group">
							<div class="input-group">
								<input name="RCFReceiverPositionX" class="form-control" placeholder="X" value="<?php echo $RCFReceiverPositionX; ?>" onchange="validateFloat('RCFReceiverPositionX');" required>
								<input name="RCFReceiverPositionY" class="form-control" placeholder="Y" value="<?php echo $RCFReceiverPositionY; ?>" onchange="validateFloat('RCFReceiverPositionY');" required>
								<input name="RCFReceiverPositionZ" class="form-control" placeholder="Z" value="<?php echo $RCFReceiverPositionZ; ?>" onchange="validateFloat('RCFReceiverPositionZ');" required>
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
								<option <?php if ($GRCSMSeverityFilter == 0) echo 'selected="selected"'; ?> value="0">No error message</option>
                                <option <?php if ($GRCSMSeverityFilter == 1) echo 'selected="selected"'; ?> value="1">Fatal</option>
                                <option <?php if ($GRCSMSeverityFilter == 3) echo 'selected="selected"'; ?> value="3">Error</option>
                                <option <?php if ($GRCSMSeverityFilter == 7) echo 'selected="selected"'; ?> value="7">Warning</option>
                                <option <?php if ($GRCSMSeverityFilter == 15) echo 'selected="selected"'; ?> value="15">Info</option>
                                <option <?php if ($GRCSMSeverityFilter == 31 || !isset($GRCSMSeverityFilter)) echo 'selected="selected"'; ?> value="31">All</option>
                                <option <?php if ($GRCSMSeverityFilter == 159) echo 'selected="selected"'; ?> value="159">Debug</option>
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
								<option <?php if ($GRDSMSeverityFilter == 0) echo 'selected="selected"'; ?> value="0">No error message</option>
                                <option <?php if ($GRDSMSeverityFilter == 1) echo 'selected="selected"'; ?> value="1">Fatal</option>
                                <option <?php if ($GRDSMSeverityFilter == 3) echo 'selected="selected"'; ?> value="3">Error</option>
                                <option <?php if ($GRDSMSeverityFilter == 7) echo 'selected="selected"'; ?> value="7">Warning</option>
                                <option <?php if ($GRDSMSeverityFilter == 15) echo 'selected="selected"'; ?> value="15">Info</option>
                                <option <?php if ($GRDSMSeverityFilter == 31 || !isset($GRDSMSeverityFilter)) echo 'selected="selected"'; ?> value="31">All</option>
                                <option <?php if ($GRDSMSeverityFilter == 159) echo 'selected="selected"'; ?> value="159">Debug</option>
                            </select>
						</div>
						
						<label>SampleRate</label>
						<div class="form-group input-group">
                            <select name="GRDSMSampleRate" class="form-control">
								<option <?php if ($GRDSMSampleRate == 10 || !isset($GRDSMSampleRate)) echo 'selected="selected"'; ?> value="10">10</option>
                                <option <?php if ($GRDSMSampleRate == 20) echo 'selected="selected"'; ?> value="20">20</option>
                                <option <?php if ($GRDSMSampleRate == 40) echo 'selected="selected"'; ?> value="40">40</option>
                                <option <?php if ($GRDSMSampleRate == 50) echo 'selected="selected"'; ?> value="50">50</option>
                                <option <?php if ($GRDSMSampleRate == 100) echo 'selected="selected"'; ?> value="100">100</option>
                                <option <?php if ($GRDSMSampleRate == 200) echo 'selected="selected"'; ?> value="200">200</option>
                                <option <?php if ($GRDSMSampleRate == 500) echo 'selected="selected"'; ?> value="500">500</option>
                                <option <?php if ($GRDSMSampleRate == 1000) echo 'selected="selected"'; ?> value="1000">1000</option>
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
								<option <?php if ($GESMSeverityFilter == 0) echo 'selected="selected"'; ?> value="0">No error message</option>
                                <option <?php if ($GESMSeverityFilter == 1) echo 'selected="selected"'; ?> value="1">Fatal</option>
                                <option <?php if ($GESMSeverityFilter == 3) echo 'selected="selected"'; ?> value="3">Error</option>
                                <option <?php if ($GESMSeverityFilter == 7) echo 'selected="selected"'; ?> value="7">Warning</option>
                                <option <?php if ($GESMSeverityFilter == 15) echo 'selected="selected"'; ?> value="15">Info</option>
                                <option <?php if ($GESMSeverityFilter == 31 || !isset($GESMSeverityFilter)) echo 'selected="selected"'; ?> value="31">All</option>
                                <option <?php if ($GESMSeverityFilter == 159) echo 'selected="selected"'; ?> value="159">Debug</option>
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
								<option <?php if ($ProcessingSeverityFilter == 0) echo 'selected="selected"'; ?> value="0">No error message</option>
                                <option <?php if ($ProcessingSeverityFilter == 1) echo 'selected="selected"'; ?> value="1">Fatal</option>
                                <option <?php if ($ProcessingSeverityFilter == 3) echo 'selected="selected"'; ?> value="3">Error</option>
                                <option <?php if ($ProcessingSeverityFilter == 7) echo 'selected="selected"'; ?> value="7">Warning</option>
                                <option <?php if ($ProcessingSeverityFilter == 15) echo 'selected="selected"'; ?> value="15">Info</option>
                                <option <?php if ($ProcessingSeverityFilter == 31 || !isset($ProcessingSeverityFilter)) echo 'selected="selected"'; ?> value="31" selected="selected">All</option>
                                <option <?php if ($ProcessingSeverityFilter == 159) echo 'selected="selected"'; ?> value="159">Debug</option>
                            </select>
						</div>
						
						<div class="form-group">
							<label>DopplerTolerance</label>
							<input name="ProcessingDopplerTolerance" class="form-control" value="<?php echo $ProcessingDopplerTolerance; ?>" onchange="validateFloat('ProcessingDopplerTolerance');" required>
						</div>
						
						<div class="form-group">
							<label>FilterFreq</label>
							<input name="ProcessingFilterFreq" class="form-control" value="<?php echo $ProcessingFilterFreq; ?>" onchange="validateFloat('ProcessingFilterFreq');" required>
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
								<option <?php if ($ICMSeverityFilter == 0) echo 'selected="selected"'; ?> value="0">No error message</option>
                                <option <?php if ($ICMSeverityFilter == 1) echo 'selected="selected"'; ?> value="1">Fatal</option>
                                <option <?php if ($ICMSeverityFilter == 3) echo 'selected="selected"'; ?> value="3">Error</option>
                                <option <?php if ($ICMSeverityFilter == 7) echo 'selected="selected"'; ?> value="7">Warning</option>
                                <option <?php if ($ICMSeverityFilter == 15) echo 'selected="selected"'; ?> value="15">Info</option>
                                <option <?php if ($ICMSeverityFilter == 31 || !isset($ICMSeverityFilter)) echo 'selected="selected"'; ?> value="31" selected="selected">All</option>
                                <option <?php if ($ICMSeverityFilter == 159) echo 'selected="selected"'; ?> value="159">Debug</option>
                            </select>
						</div>
	
						<div class="form-group">
							<label>TxIP_Address</label>
							<input name="ICMTxIP_Address" class="form-control" value="<?php echo $ICMTxIP_Address; ?>" onchange="validateIp('ICMTxIP_Address');" required>
						</div>
						
						<div class="form-group">
							<label>TxPortNo</label>
							<input name="ICMTxPortNo" class="form-control" value="<?php echo $ICMTxPortNo; ?>" onchange="validateUint('ICMTxPortNo');" required>
						</div>
						
						<div class="form-group">
							<label>TxSocketType</label>
							<input name="ICMTxSocketType" class="form-control" value="<?php echo $ICMTxSocketType; ?>" onchange="validateUint('ICMTxSocketType');" required>
						</div>
						
						<label>TxIOTimeout</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="ICMTxIOTimeout" class="form-control" value="<?php echo $ICMTxIOTimeout; ?>" onchange="validateUint('ICMTxIOTimeout');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>
						
						<label>TxConnectionTimeout</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="ICMTxConnectionTimeout" class="form-control" value="<?php echo $ICMTxConnectionTimeout; ?>" onchange="validateUint('ICMTxConnectionTimeout');" required>
								<span class="input-group-addon">sec</span>
							</div>
						</div>

						<label>TxRetryDelay</label>
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="ICMTxRetryDelay" class="form-control" value="<?php echo $ICMTxRetryDelay; ?>" onchange="validateUint('ICMTxRetryDelay');" required>
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
								<option <?php if ($OutputSeverityFilter == 0) echo 'selected="selected"'; ?> value="0">No error message</option>
                                <option <?php if ($OutputSeverityFilter == 1) echo 'selected="selected"'; ?> value="1">Fatal</option>
                                <option <?php if ($OutputSeverityFilter == 3) echo 'selected="selected"'; ?> value="3">Error</option>
                                <option <?php if ($OutputSeverityFilter == 7) echo 'selected="selected"'; ?> value="7">Warning</option>
                                <option <?php if ($OutputSeverityFilter == 15) echo 'selected="selected"'; ?> value="15">Info</option>
                                <option <?php if ($OutputSeverityFilter == 31 || !isset($OutputSeverityFilter)) echo 'selected="selected"'; ?> value="31" selected="selected">All</option>
                                <option <?php if ($OutputSeverityFilter == 159) echo 'selected="selected"'; ?> value="159">Debug</option>
                            </select>
						</div>
						
						<div class="form-group">
							<label>RootDirectory</label>
							<input name="OutputRootDirectory" class="form-control" value="<?php echo $OutputRootDirectory; ?>" onchange="validatePath('OutputRootDirectory');" required>
						</div>
					</div>
				</div>
			</div>
			
			<div class="pull-right">
				<button name="saveSend" type="button" class="btn btn-success" onclick="checkAllValid(); submitForm('manualConfigForm','saveConfigInDb.php'); submitForm('manualConfigForm','sendConfig.php');">Save & send</button>
				<button name="save" type="button" class="btn btn-primary" onclick="checkAllValid(); submitForm('manualConfigForm','saveConfigInDb.php');">Save</button>
				<button name="send" type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmNoSave" onclick="checkAllValid(); submitForm('manualConfigForm','sendConfig.php');"Send (but don't save)</button>
			</div>
			
			
			<!-- ALL THE POSSIBLE MODALS TO INFORM OF THE OUTPUT OF BUTTON PRESSING ARE STORED HERE -->
			
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
							<button type="button" class="btn btn-danger" data-dismiss="modal">I know what I'm doing.</button>
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
	//prefillForm();
	
	function submitForm(formName, action) //changes the action to be made by the button
    {
        document.getElementById(formName).action = action;
        document.getElementById(formName).submit();
    }
</script>
<script type="text/javascript" src="js/validateConfig.js"></script>
<?php include("foot.php"); ?>
