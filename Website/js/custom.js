function setActive(id) {
	document.getElementById(id).className = "active";
	return 0;
}

function prefillForm() {
	var x = document.getElementById("selectConfig").value;
    document.getElementById("name").value = (x!=0)?"You selected: " + x:"This is the active file";
    document.getElementById("number").value = x;
}
