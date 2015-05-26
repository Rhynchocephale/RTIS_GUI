<!DOCTYPE html>
<?php include("head.php"); ?>

<div class="panel panel-primary">
	<div class="panel-heading">
		  <h3 class="panel-title">Edit a configuration file</h3>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label>Choose your model</label>
			<select class="form-control">
				<option>Config file 1</option>
				<option>Config file 2</option>
				<option>Config file 3</option>
				<option>Config file 4</option>
				<option>Config file 5</option>
			</select>
			<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirmDelete"><i class="fa fa-trash"></i> Irreversibly delete this file</button>
		</div>
		<br/>
		
		<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="confirmDelete" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Are you sure you want to do this?</h4>
					</div>
					<div class="modal-body">
						<h3>This file will be deleted forever. Do you really want to continue?</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Yep, sure.</button>
						<button type="button" class="btn btn-success" data-dismiss="modal">Well, not really.</button>
					</div>
				</div>
			</div>
		</div>
		
		<form role="form">
			<div class="col-lg-6">
				
				<div class="form-group">
					<label>Write stuff below</label>
					<input class="form-control">
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
					<label>Write more stuff here</label>
					<input class="form-control">
				</div>
				
			</div>
	
			<button type="button" class="btn btn-primary">Save</button>
			<button type="button" class="btn btn-success">Save & send</button>
			<button type="button" class="btn btn-warning">Send (but don't save)</button>
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
			
			<button type="button" class="btn btn-primary">Save</button>
			<button type="button" class="btn btn-success">Save & send</button>
			<button type="button" class="btn btn-warning">Send (but don't save)</button>
		</form>
	</div>
</div>

<script>setActive("li-config");</script>
<?php include("foot.php"); ?>
