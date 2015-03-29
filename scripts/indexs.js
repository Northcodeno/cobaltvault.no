function setHeight() {
	console.log("Heeey");
	var h = 0;
	$('.thumbnail').each(function(i, obj) {
		console.log(obj.clientHeight);
		if(obj.clientHeight > h)
			h = obj.clientHeight;
	});
	console.log("H: " + h);
	$('.thumbnail').each(function(i, obj) {
		obj.style.height = h + "px";
	});
}

$(document).ready(function () {
	setHeight();
});