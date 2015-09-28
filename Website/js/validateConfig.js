function validateUint(id) {
	var myElement = $("[name="+id+"]");
	myElement.removeClass("has-error has-success");
	$("#"+id+'_Label').remove();   				//removing the error label if present
	if( myElement.val() ) { 					//if is not empty
		if (/^\d+$/.test(myElement.val())){		//only digits
			myElement.addClass("has-success");
		} else {
			myElement.addClass("has-error");
			if(! myElement.parent().hasClass("form-group")) { 		//going one step higher, in order to get out of input-groups
					myElement = myElement.parent();
			}
			myElement.after( "<div id=\""+id+"_Label\" class=\"label label-danger\">Invalid int</div>" ); //adding an error label after the current element
		}
	}
}

function validatePort(id) {
	var myElement = $("[name="+id+"]");
	myElement.removeClass("has-error has-success");
	$("#"+id+'_Label').remove();   				//removing the error label if present
	if( myElement.val() ) { 					//if is not empty
		if (/^([1-9][0-9]{0,3}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])$/.test(myElement.val())){		//between 1 and 65536
			myElement.addClass("has-success");
		} else {
			myElement.addClass("has-error");
			if(! myElement.parent().hasClass("form-group")) { 		//going one step higher, in order to get out of input-groups
					myElement = myElement.parent();
			}
			myElement.after( "<div id=\""+id+"_Label\" class=\"label label-danger\">Invalid port</div>" ); //adding an error label after the current element
		}
	}
}

function validateFloat(id) {
	var myElement = $("[name="+id+"]");
	myElement.removeClass("has-error has-success")
	$("#"+id+'_Label').remove();   			//removing the error label if present
	if( myElement.val() ) { 				//if is not empty
		if (/^\d+(\.\d+)?$/.test(myElement.val())){  // only digits optionally followed by a dot and more digits
			myElement.addClass("has-success");
		} else {
			myElement.addClass("has-error");
			if(! myElement.parent().hasClass("form-group")) { //going one step higher, in order to get out of input-groups
				myElement = myElement.parent();
			}
			if(! myElement.next().hasClass("label label-danger")){ //if no label is already present (special case for ReceiverPosition)
				myElement.after( "<div id=\""+id+"_Label\" class=\"label label-danger\">Invalid float</div>" );
			}
		}
	}
}	

function validatePath(id) {
	var myElement = $("[name="+id+"]");
	myElement.removeClass("has-error has-warning has-success");
	$("#"+id+'_Label').remove();   			//removing the error label if present
	if( myElement.val() ) { 				//if is not empty
		if (!/^\//.test(myElement.val())){  //does not start with a slash
			myElement.addClass("has-warning");
			myElement.after("<div id=\""+id+"_Label\" class=\"label label-warning\">Should start with /</div>");
		} else {
			if (/^\/([ÆØŒÅÉÈÊÓÒÂÔæøœåéèêóòâôa-zA-Z0-9\-\.\_\/ ]+)?$/.test(myElement.val())){ //only authorised characters
				myElement.addClass("has-success");
			} else {
				myElement.addClass("has-danger");
				myElement.after("<div id=\""+id+"_Label\" class=\"label label-danger\">Illegal character</div>");
			}
		}
	}
}

function validateIp(id) {
	var myElement = $("[name="+id+"]");
	var myRegex = /^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))\.){3}((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))$/;
	
	myElement.removeClass("has-error has-success");
	$("#"+id+'_Label').remove();   	//removing the error label if present
	if( myElement.val() ) { 		//if is not empty
		if (myRegex.test(myElement.val())){  //four int between 0 and 255 separated by dots
			myElement.addClass("has-success");
		} else {
			myElement.addClass("has-error");
			myElement.after( "<div id=\""+id+"_Label\" class=\"label label-danger\">Invalid IP</div>" );
		}
	}
}

function validateStr(id) {
	var myElement = $("[name="+id+"]");
	myElement.removeClass("has-error has-success");
	$("#"+id+'_Label').remove();   	//removing the error label if present
	if( myElement.val() ) { 		//if is not empty
		if (/^[ÆØÅæøåa-zA-Z0-9\-\.\_ ]*$/.test(myElement.val())){  //only authorised characters
			myElement.addClass("has-success");
		} else {
			myElement.addClass("has-error");
			myElement.after( "<div id=\""+id+"_Label\" class=\"label label-danger\">Illegal character</div>" ); //adding an error label after the current element
		}
	}
}

function checkAllValid() {
	var isOk = true;
	$('#manualConfigForm.form-control').each( function (index, data) {
		var currentElement = $(this);
		if(currentElement.hasClass("has-error") ) { //if wrong value
			isOk = false;
		}
		
		if(! currentElement.val() && !currentElement.hasClass("optional")) { //if empty and should not be
			isOk = false;
			currentElement.addClass("has-error");
			if(! currentElement.parent().hasClass("form-group")) { 			//going one step higher, in order to get out of input-groups
					currentElement = currentElement.parent();
			}
			if(! currentElement.next().hasClass("label label-danger")){ 	//if no label is already present (special case for ReceiverPosition)
				currentElement.after( "<div id=\"" + currentElement.attr("id") + "_Label\" class=\"label label-danger\">Empty</div>" );
			}
		}
	})
	
	return isOk;
}
