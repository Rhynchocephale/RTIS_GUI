<!DOCTYPE html>
<?php include("head.php"); ?>
		
<div class="col-lg-12">

	<!-- LIST OF PROCESS -->
	<div class="panel panel-primary">
		<div class="panel-heading clearfix">
			<h3 class="panel-title pull-left">
				<i class="fa fa-list"></i> List of active processes
			</h3>
			<div class="form-group input-group col-lg-2 pull-right">
				<input type="text" class="form-control input-sm" placeholder="Refresh frequency">
				<span class="input-group-addon input-sm">seconds</span>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Process name</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>mainProcess.cpp</td>
					</tr>
					<tr>
						<td>2</td>
						<td>firstSubProcess.cpp</td>
					</tr>
					<tr>
						<td>3</td>
						<td>yetAnotherSubProcess.py</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
		
	<!-- ALARM MESSAGES -->
	<div class="panel panel-primary">
		<div class="panel-heading" data-toggle="collapse" data-target="#errorMessages">
			<h3 class="panel-title"><i class="fa fa-rss"></i> Application messages </h3>
		</div>
		<div id="errorMessages" class="panel-body collapse in">
			<section class="feed-item feed-info">
				<div class="feed-item-body">
					<div class="text">
						This is an informative message.
					</div>
					<div class="time pull-left">
						10:45:34,657
					</div>
				</div>
			</section>
						
			<section class="feed-item feed-warning">
				<div class="feed-item-body">
					<div class="text">
						This is a warning message.
					</div>
					<div class="time pull-left">
						10:45:34,317
					</div>
				</div>
			</section>
						
			<section class="feed-item feed-danger">
				<div class="feed-item-body">
					<div class="text">
						This is a critical failure message.
					</div>
					<div class="time pull-left">
						10:45:32,543
					</div>
				</div>
			</section>
							
			<section class="feed-item feed-info">
				<div class="feed-item-body">
					<div class="text">
						This is another informative message.
					</div>
					<div class="time pull-left">
						10:45:31,965
					</div>
				</div>
			</section>
						
		</div>
	</div>
</div>

<script>setActive("li-data");</script>
<?php include("foot.php"); ?>
