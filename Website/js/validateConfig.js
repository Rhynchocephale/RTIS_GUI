function validateUint(id) {
	var myElement = $("#"+id);
	myElement.removeClass("has-error has-success");
	if( myElement.val() ) { //if is not empty
		if (/^\d+$/.test(myElement.val())){  //only digits
			myElement.addClass("has-success");
			$("#"+id+'_Label').remove();     //removing the error label
		} else {
			myElement.addClass("has-error");
			if (! $("#"+id+'_Label').length) { //if no label is already present
				if(! myElement.parent().hasClass("form-group")) { //going one step higher, in order to get out of input-groups
					myElement = myElement.parent();
				}
				myElement.after( "<div id=\""+id+"_Label\" class=\"label label-danger\">Invalid int</div>" ); //adding an error label after the current element
			}
		}
	} else {
		$("#"+id+'_Label').remove();
	}
}

function validateFloat(id) {
	var myElement = $("#"+id);
	myElement.removeClass("has-error has-success")
	
	if( myElement.val() ) { //if is not empty
		if (/^\d+(\.\d+)?$/.test(myElement.val())){  // only digits optionally followed by a dot and more digits
			myElement.addClass("has-success");
			$("#"+id+'_Label').remove(); //removing the error label
		} else {
			myElement.addClass("has-error");
			if (! $("#"+id+'_Label').length) { 
				if(! myElement.parent().hasClass("form-group")) { //going one step higher, in order to get out of input-groups
					myElement = myElement.parent();
				}
				if(! myElement.next().hasClass("label label-danger")){ //if no label is already present (special case for ReceiverPosition)
					myElement.after( "<div id=\""+id+"_Label\" class=\"label label-danger\">Invalid float</div>" );
				}
			}
		}
	} else {
		$("#"+id+'_Label').remove();
	}
}	

function validatePath(id) {
	var myElement = $("#"+id);
	myElement.removeClass("has-warning has-success");
	if( myElement.val() ) { //if is not empty
		if (/^\//.test(myElement.val())){  //only digits
			myElement.addClass("has-success");
			$("#"+id+'_Label').remove(); //removing the error label
				
		} else {
			myElement.addClass("has-warning");
			if (! $("#"+id+'_Label').length) {
				myElement.after( "<div id=\""+id+"_Label\" class=\"label label-warning\">Should start with /</div>" );
			}
		}
	} else {
		$("#"+id+'_Label').remove();
	}
}

function validateIp(id) {
	var myElement = $("#"+id);
	var myRegex = /^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))\.){3}((\d{1,2})|(1\d{2})|(2[0-4]\d)|(25[0-5]))$/;
	
	myElement.removeClass("has-error has-success");
	if( myElement.val() ) { //if is not empty
		if (myRegex.test(myElement.val())){  //only digits
			myElement.addClass("has-success");
			$("#"+id+'_Label').remove(); //removing the error label
				
		} else {
			myElement.addClass("has-error");
			if (! $("#"+id+'_Label').length) {
				myElement.after( "<div id=\""+id+"_Label\" class=\"label label-danger\">Invalid IP</div>" );
			}
		}
	} else {
		$("#"+id+'_Label').remove();
	}
}

function checkAllValid() {
	isOk = true;
	$('.form-control').each( function (index, data) {
		var currentElement = $(this);
		if(currentElement.hasClass("has-error") ) { //if wrong value
			isOk = false;
		}
		
		if(! currentElement.val() && !currentElement.hasClass("optional")) { //if empty and should not be
			isOk = false;
			currentElement.addClass("has-error");
			if(! currentElement.parent().hasClass("form-group")) { //going one step higher, in order to get out of input-groups
					currentElement = currentElement.parent();
			}
			if(! currentElement.next().hasClass("label label-danger")){ //if no label is already present (special case for ReceiverPosition)
				currentElement.after( "<div id=\"" + currentElement.attr("id") + "_Label\" class=\"label label-danger\">Empty</div>" );
			}
		}
	})
	
	return isOk;
}
