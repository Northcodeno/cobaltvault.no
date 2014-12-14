var cw = $('.dropzone').width();
$('.dropzone').css({
    'min-height': cw + 'px'
});

Dropzone.options.fileUploads = {
	init: function() {
		this.on("success", function(file, responseText) {
			file.previewElement.querySelector(".progress").className = "progress";
			file.previewElement.querySelector(".responseText").textContent = responseText;
			$.get('/ajax/project_file_list.php?id=<?php echo $Project->id; ?>', function(data) {
				$("#file-list").html(data);
			});
		});
	}
}