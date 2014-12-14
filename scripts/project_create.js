var isValidName = function(name) {
	return (name.indexOf(">") == -1 && name.indexOf("<") == -1);
};

var isValidIdName = function(idname) {
	var pattern = new RegExp(/^[a-z]*$/);
	return pattern.test(idname);
};

var isValidDesc = function(desc) {
	var pattern = new RegExp(/\<script/);
	return pattern.test(desc);
}


var delay = (function(){
	var timer = 0;
	return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();

$(function(ready) {
	$("input.form-control[data-check='true']").each(function() {
		var fg = $(this).parent().parent();
		var input = $(this);
		input.on('input',function() {
		
			if(input.val() == "")
			{
				fg.attr('class','form-group has-warning');
			}
			else
			{
				var valid = true;
				switch(input.attr('name'))
				{
					case "name":
						var valid = isValidName(input.val());
						break;
					case "idname":
						var valid = isValidIdName(input.val());
						break;
					case "desc":
						var valid = isValidDesc(input.val());
						break;
				}

				if(valid)
					fg.attr('class','form-group has-success');
				else
					fg.attr('class','form-group has-error');
			}
		});
	});
});