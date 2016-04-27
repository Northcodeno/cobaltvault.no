$(document).ready(function () {
	$(".commentbtn").click(function () {
		$("#replyto").attr('value', $(this).attr('replyid'));
		$("#post-comment").attr('value', 'Post reply to ' + $(this).attr('replyname'));
		setTimeout(function() { $("#id_message").focus(); }, 200);
	});
});