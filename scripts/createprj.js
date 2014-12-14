function IsJson(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}

function checkLoc()
{
	var text = $("#localization").val();
	if(text == "")
	{
		$("#error_localization").attr("class","col-sm-8");
	}
	else
	{
		var valid = IsJson(text);
		if(!valid)
		{
			$("#error_localization").attr("class","col-sm-8 has-error");
		}
		else
		{
			$("#error_localization").attr("class","col-sm-8 has-success");
		}
	}
}

function strip(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent||tmp.innerText;
}

function autoGenLoc()
{
	var loc = {};
	loc.eng = {};
	try
	{
		loc.eng.name = $("#name").val();
		loc.eng.description = strip(tinyMCE.get('desc').getContent());
	}
	catch(err)
	{
		loc.eng.name = "TYPE NAME HERE";
		loc.eng.description = "SHORT DESCRIPTION HERE";
	}

	var localization = '{\n    eng = {\n        name = "' + loc.eng.name + '",\n        description = "' + loc.eng.description + '"\n    }\n}';

	$("#localization").val(localization);
}


$("document").ready(function() {
	/*window.setInterval(function() {
		checkLoc();
	},500);*/

	$("#loc_auto").click(function() {
		autoGenLoc();
	});
});