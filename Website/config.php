<!DOCTYPE html>
<?php
	include("head.php"); 
	include("database.php");
	connectToDb();
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
				<button name="save" type="button" class="btn btn-primary">Save</button>
				<button name="saveSend" type="button" class="btn btn-success">Save & send</button>
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
		<div class="form-group">
			<label>Choose your model</label>
			<select id="selectConfig" class="form-control" onchange="prefillForm()">
				<option value="0">Active config file</option>
				<option value="1">Config file 1</option>
				<option value="2">Config file 2</option>
				<option value="3">Config file 3</option>
				<option value="4">Config file 4</option>
				<option value="5">Config file 5</option>
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
						<button type="button" class="btn btn-danger" data-dismiss="modal">Yep, sure.</button>
						<button type="button" class="btn btn-success" data-dismiss="modal">Well, not really.</button>
					</div>
				</div>
			</div>
		</div>
		
		<form role="form" method="post" action="submitConfig.php">
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading">RTIM Configuration file</div>
					<div class="panel-footer">
				
						<div class="form-group">
							<label>SeverityFilter</label>
							<select id="RCFSeverityFilter"class="form-control">
								<option value="0">No error message</option>
                                <option value="1">Fatal</option>
                                <option value="3">Error</option>
                                <option value="7">Warning</option>
                                <option value="15">Info</option>
                                <option value="31">All</option>
                                <option value="159">Debug</option>
                            </select>
						</div>

						<div class="form-group">
							<label>StationId</label>
							<input id="RCFStationId" class="form-control" onchange="validateUint('RCFStationId');">
						</div>
						
						<div class="form-group">
							<label>RxIP_Address</label>
							<input id="RCFRxIP_Address" class="form-control"  onchange="validateIp('RCFRxIP_Address');">
						</div>
						
						<div class="form-group">
							<label>RxPortNo</label>
							<input id="RCFRxPortNo" class="form-control" onchange="validateUint('RCFRxPortNo');">
						</div>
						
						<div class="form-group">
							<label>RxSocketType</label>
							<input id="RCFRxSocketType" class="form-control" onchange="validateUint('RCFRxSocketType');">
						</div>
						
						<label>RxIOTimeout</label>
						<div class="form-group input-group">
							<input type="text" id="RCFRxIOTimeout" class="form-control" onchange="validateUint('RCFRxIOTimeout');">
                            <span class="input-group-addon">sec</span>
						</div>
						
						<label>RxConnectionTimeout</label>
						<div class="form-group input-group">
							<input type="text" id="RCFRxConnectionTimeout" class="form-control" onchange="validateUint('RCFRxConnectionTimeout');">
                            <span class="input-group-addon">sec</span>
						</div>
						
						<label>RxRetryDelay</label>
						<div class="form-group input-group">
							<input type="text" id="RCFRxRetryDelay" class="form-control" onchange="validateUint('RCFRxRetryDelay');">
                            <span class="input-group-addon">sec</span>
						</div>
						
						<div class="form-group">
							<label>StationShortName</label>
							<input id="RCFStationShortName" class="form-control">
						</div>
						
						<label>ReceiverPosition</label>
						<div class="form-group input-group">
							<input id="RCFReceiverPositionX" class="form-control" placeholder="X" onchange="validateFloat('RCFReceiverPositionX');">
							<input id="RCFReceiverPositionY" class="form-control" placeholder="Y" onchange="validateFloat('RCFReceiverPositionY');">
							<input id="RCFReceiverPositionZ" class="form-control" placeholder="Z" onchange="validateFloat('RCFReceiverPositionZ');">
							<span class="input-group-addon">m</span>
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
							<select id="GRCSMSeverityFilter" class="form-control">
								<option value="0">No error message</option>
                                <option value="1">Fatal</option>
                                <option value="3">Error</option>
                                <option value="7">Warning</option>
                                <option value="15">Info</option>
                                <option value="31">All</option>
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
							<select id="GRDSMSeverityFilter" class="form-control">
								<option value="0">No error message</option>
                                <option value="1">Fatal</option>
                                <option value="3">Error</option>
                                <option value="7">Warning</option>
                                <option value="15">Info</option>
                                <option value="31">All</option>
                                <option value="159">Debug</option>
                            </select>
						</div>
						
						<label>SampleRate</label>
						<div class="form-group input-group">
                            <select id="GRDSMSampleRate" class="form-control">
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
							<select id="GESMSeverityFilter" class="form-control">
								<option value="0">No error message</option>
                                <option value="1">Fatal</option>
                                <option value="3">Error</option>
                                <option value="7">Warning</option>
                                <option value="15">Info</option>
                                <option value="31">All</option>
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
							<select id="ProcessingSeverityFilter" class="form-control">
								<option value="0">No error message</option>
                                <option value="1">Fatal</option>
                                <option value="3">Error</option>
                                <option value="7">Warning</option>
                                <option value="15">Info</option>
                                <option value="31">All</option>
                                <option value="159">Debug</option>
                            </select>
						</div>
						
						<div class="form-group">
							<label>DopplerTolerance</label>
							<input id="ProcessingDopplerTolerance" class="form-control" onchange="validateFloat('ProcessingDopplerTolerance');">
						</div>
						
						<div class="form-group">
							<label>FilterFreq</label>
							<input id="ProcessingFilterFreq" class="form-control" onchange="validateFloat('ProcessingFilterFreq');">
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
							<select id="ICMSeverityFilter" class="form-control">
								<option value="0">No error message</option>
                                <option value="1">Fatal</option>
                                <option value="3">Error</option>
                                <option value="7">Warning</option>
                                <option value="15">Info</option>
                                <option value="31">All</option>
                                <option value="159">Debug</option>
                            </select>
						</div>
						
						<div class="form-group">
							<label>TxIP_Address</label>
							<input id="ICMTxIP_Address" class="form-control" onchange="validateIp('ICMTxIP_Address');">
						</div>
						
						<div class="form-group">
							<label>TxPortNo</label>
							<input id="ICMTxPortNo" class="form-control" onchange="validateUint('ICMTxPortNo');">
						</div>
						
						<div class="form-group">
							<label>TxSocketType</label>
							<input id="ICMTxSocketType" class="form-control" onchange="validateUint('ICMTxSocketType');">
						</div>
						
						<label>TxIOTimeout</label>
						<div class="form-group input-group">
							<input type="text" id="ICMTxIOTimeout" class="form-control" onchange="validateUint('ICMTxIOTimeout');">
                            <span class="input-group-addon">sec</span>
						</div>
						
						<label>TxConnectionTimeout</label>
						<div class="form-group input-group">
							<input type="text" id="ICMTxConnectionTimeout" class="form-control" onchange="validateUint('ICMTxConnectionTimeout');">
                            <span class="input-group-addon">sec</span>
						</div>

						<label>TxRetryDelay</label>
						<div class="form-group input-group">
							<input type="text" id="ICMTxRetryDelay" class="form-control" onchange="validateUint('ICMTxRetryDelay');">
							<span class="input-group-addon">sec</span>
						</div>
					</div>
				</div>
					
				<div class="panel panel-default">
					<div class="panel-heading">Output</div>
					<div class="panel-footer">
			
						<div class="form-group">
							<label>SeverityFilter</label>
							<select id="OutputSeverityFilter" class="form-control">
								<option value="0">No error message</option>
                                <option value="1">Fatal</option>
                                <option value="3">Error</option>
                                <option value="7">Warning</option>
                                <option value="15">Info</option>
                                <option value="31">All</option>
                                <option value="159">Debug</option>
                            </select>
						</div>
						
						<div class="form-group">
							<label>RootDirectory</label>
							<input id="OutputRootDirectory" class="form-control" onchange="validatePath('OutputRootDirectory');">
						</div>
					</div>
				</div>
			</div>
			
			<div class="pull-right">
				<button name="save" type="button" class="btn btn-primary" >Save</button>
				<button name="saveSend" type="button" class="btn btn-success">Save & send</button>
				<button name="send" type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmNoSave">Send (but don't save)</button>
			</div>
			
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
	prefillForm();
</script>
<script type="text/javascript" src="js/validateConfig.js"></script>
<?php include("foot.php"); ?>
