<!DOCTYPE html>
<?php
	include("head.php"); 
	include("database.php");
	connectToDb();
?> 

<div class="panel panel-primary">
	
	<div class="panel-heading">
		  <h3 class="panel-title">Edit a configuration file</h3>
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
			<div class="col-lg-6">
				
				<div class="form-group">
					<label>Write stuff below</label>
					<input id="name" class="form-control">
				</div>

				<div class="form-group">
					<label>Write more stuff here</label>
					<input class="form-control">
				</div>
				
				<div class="form-group">
					<label>Stuff to check</label>
					<label class="checkbox-inline">
						<input type="checkbox">
						1
					</label>
					<label class="checkbox-inline">
						<input type="checkbox">
						2
					</label>
					<label class="checkbox-inline">
						<input type="checkbox">
						3
					</label>
				</div>
				
			</div>
			<div class="col-lg-6">
				
				<div class="form-group">
					<label>Write stuff in another column</label>
					<input class="form-control">
				</div>

				<div class="form-group">
					<label>Check one of these stuff</label>
					<label class="radio-inline">
						<input type="radio" name="optionsRadiosInline" id="optionsRadiosInline1" value="option1" checked>
						1
					</label>
					<label class="radio-inline">
						<input type="radio" name="optionsRadiosInline" id="optionsRadiosInline2" value="option2">
						2
					</label>
					<label class="radio-inline">
						<input type="radio" name="optionsRadiosInline" id="optionsRadiosInline3" value="option3">
						3
					</label>
				</div>

				<div class="form-group">
					<label>Select stuff</label>
					<select id="number" class="form-control">
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
				
			</div>
	
			<button name="save" type="button" class="btn btn-primary">Save</button>
			<button name="saveSend" type="button" class="btn btn-success">Save & send</button>
			<button name="send" type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmNoSave">Send (but don't save)</button>
		
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

<div class="panel panel-primary">
	<div class="panel-heading">
		  <h3 class="panel-title">Load your own file</h3>
	</div>
	<div class="panel-body">
		<form role="form">
			<div class="col-lg-12">
				<div class="form-group">
					<label>Select your file</label>
					<input type="file">
				</div>
				
			</div>
			
			<button name="saveFile" type="button" class="btn btn-primary">Save</button>
			<button name="saveSendFile" type="button" class="btn btn-success">Save & send</button>
			<button name="sendFile" type="button" class="btn btn-warning">Send (but don't save)</button>
		</form>
	</div>
</div>

<script>
	setActive("li-config");
	prefillForm();
</script>
<?php include("foot.php"); ?>
