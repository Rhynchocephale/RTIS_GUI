<!DOCTYPE html>
<?php include("head.php"); ?>
		
<div class="col-lg-12">

	<!-- LIST OF PROCESS -->
	<div class="panel panel-primary">
		<div class="panel-heading" data-toggle="collapse" data-target="#listOfProcess">
			<h3 class="panel-title">
				<i class="fa fa-list"></i> List of active processes
			</h3>
		</div>
		<div id="listOfProcess" class="table-responsive collapse in">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Process name</th>
					</tr>
				</thead>
				<tbody id="processBody">
				</tbody>
			</table>
		</div>
		<div id="processFooter" class="panel-footer">
			<label>Update?</label>
			<label class="radio-inline">
				<input type="radio" name="update_processRadio" id="processYes" onclick="$('#processValue').removeAttr('disabled');updateValue('process');">
				Yes
			</label>
			<label class="radio-inline">
				<input type="radio" name="update_processRadio" id="processNo" onclick="$('#processValue').attr('disabled','disabled');stopUpdate('process');" checked>
				No
			</label>
			<div class="input-group col-lg-3">
				<input type="number" min="1" value="600" id="processValue" class="form-control input-sm" placeholder="Refresh frequency" onchange="updateValue('process');" disabled="disabled">
				<span class="input-group-addon input-sm">seconds</span>
			</div>
		</div>
	</div>
		
	<!-- MONITORING TABLE -->
	<div class="panel panel-primary">
		<div class="panel-heading" data-toggle="collapse" data-target="#monitorTable">
			<h3 class="panel-title">
				<i class="fa fa-list"></i> Monitoring Table
			</h3>
		</div>
		<div id="monitorTable" class="table-responsive collapse in">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Parameter</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody id="monitorBody">
				</tbody>
			</table>
		</div>
		<div id="monitorFooter" class="panel-footer">
			<label>Update?</label>
			<label class="radio-inline">
				<input type="radio" name="update_monitorRadio" id="monitorYes" onclick="$('#monitorValue').removeAttr('disabled');updateValue('monitor');" checked>
				Yes
			</label>
			<label class="radio-inline">
				<input type="radio" name="update_monitorRadio" id="monitorNo" onclick="$('#monitorValue').attr('disabled','disabled');stopUpdate('monitor');" >
				No
			</label>
			<div class="input-group col-lg-3">
				<input type="number" min="1" value="1" id="monitorValue" class="form-control input-sm" placeholder="Refresh frequency" onchange="updateValue('monitor');">
				<span class="input-group-addon input-sm">seconds</span>
			</div>
		</div>
	</div>
		
	<!-- ALARM MESSAGES -->
	<div class="panel panel-primary">
		<div class="panel-heading clearfix" data-toggle="collapse" data-target="#messagesBody">
			<h3 class="panel-title"><i class="fa fa-rss"></i> Application messages </h3>
		</div>
		<div class="panel-footer form-group form-inline">
			<label>Severity filter</label>
			<select id="messagesValue" onchange="updateMessagesValue();">
				<option value="1">Fatal</option>
				<option value="3">Error and all above</option>
				<option value="7">Warning and all above</option>
				<option value="15" selected="selected">Info and all above</option>
				<option value="0">No error message</option>
			</select>
			
			<div class="checkbox">
				<label>
					<input id="messagesDebug" type="checkbox" onchange="updateMessagesValue();">
					Debug
				</label>
			</div>
			
			<div class="checkbox">
				<label>
					<input id="messagesNotice" type="checkbox" onchange="updateMessagesValue();">
					Notice
				</label>
			</div>
			
		</div>
		<div id="messagesBody" class="panel-body collapse in">						
		</div>
	</div>
</div>

<script type="text/javascript">
	setActive("li-data");
	
	var station = <?php echo $_GET["sta"]; ?>;
				
	var corres =	{"process":{"script":"php/C_connection/sendProcess.php","param":600},
					"monitor":{"script":"php/C_connection/sendMonitor.php","param":1},
					"messages":{"script":"php/C_connection/sendMessages.php","param":31}};
					
	var isEventSupported;
	//check for browser support
	if(typeof(EventSource)!=="undefined") {
		var isEventSupported = true;
		var maxMessageLength = 7;
		
		//on page load
		getInfoOnce('process');
		updateValue('monitor');
		updateMessagesValue();

	}
	else {
		var isEventSupported = false;
		for (var key in corres) {
			if (corres.hasOwnProperty(key)) {  //avoiding inherited properties
				$("#"+key+"Body").html("Oops! Your browser doesn't receive server-sent events.");
			}
		}	
	}
	
	function updateValue(param) {
		if(!isEventSupported) {
			return false;
		}
		
		corres[param]["param"] = $("#"+param+"Value").val();
		stopUpdate(param);
		//create an object, passing it the name and location of the server side script
		corres[param]["eSource"] = new EventSource(corres[param]["script"]+"?mon="+corres["monitor"]["param"]+"&proc="+corres["process"]["param"]+"&err="+corres["messages"]["param"]+"&sta="+station);
		//detect message receipt
		corres[param]["eSource"].onmessage = function(event) {
		//write the received data to the page
			$("#"+param+"Body").html(event.data);
		};
		return true;
	}
	
	function updateMessagesValue() {
		if(!isEventSupported) {
			return false;
		}
		
		var finalValue = +$("#messagesValue").val(); //unary + casts to int
		if($('#messagesNotice').is(":checked")) {
			finalValue += 16;
		}
		if($('#messagesDebug').is(":checked")) {
			finalValue += 128;
		}
		
		corres["messages"]["param"] = finalValue;
		stopUpdate("messages");
		//create an object, passing it the name and location of the server side script
		corres["messages"]["eSource"] = new EventSource(corres["messages"]["script"]+"?mon="+corres["monitor"]["param"]+"&proc="+corres["process"]["param"]+"&err="+corres["messages"]["param"]+"&sta="+sta);
		//detect message receipt
		corres["messages"]["eSource"].onmessage = function(event) {
		//write the received data to the page
			if($("#messagesBody .feed-item").length >= maxMessageLength) {
				$("#messagesBody .feed-item:last-child").remove();
			}
			$("#messagesBody").html(event.data + $("#messagesBody").html());
		};
		return true;
	}
	
	function getInfoOnce(param) {
		//create an object, passing it the name and location of the server side script
		corres[param]["eSource"] = new EventSource(corres[param]["script"]+"?mon="+corres["monitor"]["param"]+"&proc="+corres["process"]["param"]+"&err="+corres["messages"]["param"]+"&sta="+sta);
		//detect message receipt
		corres[param]["eSource"].onmessage = function(event) {
		//write the received data to the page
			$("#"+param+"Body").html(event.data);
			stopUpdate(param);
		};
		return true;
	}
	
	function stopUpdate(param){
		if(!isEventSupported) {
			return false;
		}
		
		if(corres[param]["eSource"]) {
			corres[param]["eSource"].close();
			return true;
		}
		
		return false;
	}
	
</script>
<?php include("foot.php"); ?>
