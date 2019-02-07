function addElement(template, parent){
	var div1 = document.createElement('div');
	div1.innerHTML = document.getElementById(template).innerHTML;

	document.getElementById(parent).appendChild(div1);
}