function validateUint(id) {
	var myClass = document.getElementById(id).parentNode.className;
	myClass = myClass.replace(" has-success","").replace(" has-error","");
	
	if (/^\d+$/.test(document.getElementById(id).value)){  //only digits
		myClass += " has-success";
		document.getElementById(id+"_Label").remove();
	} else {
		myClass += " has-error";
		if(!document.getElementById(id + "_Label")) {      //if does not already exist
			var msg = document.createElement("p");
			msg.className = "label label-danger pull-right";
			msg.id = id + "_Label";
			msg.innerHTML = "Not a valid int format";
			document.getElementById(id).parentNode.appendChild(msg);
		}
	}
	
	document.getElementById(id).parentNode.className = myClass;
}

function validateFloat(id) {
	var myClass = document.getElementById(id).parentNode.className;
	myClass = myClass.replace(" has-success","").replace(" has-error","");
	
	if (/^\d+(\.\d+)?$/.test(document.getElementById(id).value)){  //unsigned float
		myClass += " has-success";
		document.getElementById(id+"_Label").remove();
	} else {
		myClass += " has-error";
		if(!document.getElementById(id + "_Label")) {      //if does not already exist
			var msg = document.createElement("span");
			msg.className = "label label-danger";
			msg.id = id + "_Label";
			msg.innerHTML = "Not a valid float format";
			document.getElementById(id).parentNode.appendChild(msg);
		}
	}
	document.getElementById(id).parentNode.className = myClass;
}

function validatePath(id) {
	var myClass = document.getElementById(id).parentNode.className;
	myClass = myClass.replace(" has-success","").replace(" has-warning","");
	
	if (/^\/.+/.test(document.getElementById(id).value)){  // starting with a slash
		myClass += " has-success";
		document.getElementById(id+"_Label").remove();
	} else {
		myClass += " has-warning";
		if(!document.getElementById(id + "_Label")) {      //if does not already exist
			var msg = document.createElement("span");
			msg.className = "label label-danger";
			msg.id = id + "_Label";
			msg.innerHTML = "Absolute paths start with /";
			document.getElementById(id).parentNode.appendChild(msg);
		}
	}
	document.getElementById(id).parentNode.className = myClass;
}

function validateIp(id) {
	var myClass = document.getElementById(id).parentNode.className;
	myClass = myClass.replace(" has-success","").replace(" has-error","");
	myRegex = /^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))\.){3}((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))$/;
	
	
	if (myRegex.test(document.getElementById(id).value)){  // four int in the range 0-255 separated by dots
		myClass += " has-success";
		document.getElementById(id+"_Label").remove();
	} else {
		myClass += " has-error";
		if(!document.getElementById(id + "_Label")) {      //if does not already exist
			var msg = document.createElement("span");
			msg.className = "label label-danger";
			msg.id = id + "_Label";
			msg.innerHTML = "An IP consists of four INT in the range 0-255 separated by dots";
			document.getElementById(id).parentNode.appendChild(msg);
		}
	}
	
	document.getElementById(id).parentNode.className = myClass;
}
