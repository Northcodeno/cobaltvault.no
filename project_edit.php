<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/resources/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/lib.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/resources/prehead.php");
if(!isset($_GET['id']))
	alert("Project not found!","danger","/list");
$id = mysql_escape_string($_GET['id']);

$Project = new Project($_GET['id']);
$edit = isset($_GET['edit']);
$owner = 0;
if($NC_LOGGED_IN)
if(!$Project->isAuthor($NC_USERINFO['uid']))
	header("Location: /project/" . $_GET['id']);

$coltype = "col-md-6";

$i = 0;

?>

<!DOCTYPE html>
<html>
<head>
	<title>Manage "<?php echo $Project->name; ?>" | Cobalt Vault</title>
	<meta name="author" content="<?php echo $Project->prettyAuthors(); ?>">
	<meta name="description" content="<?php echo $Project->shortDesc(); ?>">
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/resources/head.php"); include($_SERVER['DOCUMENT_ROOT'] . "/resources/project_head.php"); ?>
	<link rel="stylesheet" href="/style/project.css">
	<link rel="stylesheet" href="/style/star.css">
	<link rel="stylesheet" href="/style/project_edit.css">
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/resources/header.php"); ?>

	<div class="container">
		<a href="/project/<?php echo $Project->idname; ?>" id="back-button" class="btn btn-default">&laquo; Back to Project</a>
		<div class="row">
			<div class="col-md-6">
				<h1>Files</h1>
				<div id="file-list">
				<ul class="list-group">
				<?php
				foreach($Project->files as $file)
				{
					?>
					<li class="list-group-item">
					<?php echo $file->getGlyphicon() . ' ' .$file->filename; ?> <a href="/scripts/project.php?id=<?php echo $Project->id; ?>&a=delfile&file=<?php echo $file->id; ?>" class="btn btn-danger pull-right">Delete</a>
					</li>
					<?php
				}
				?>
				</ul>
				</div>

				<h1>Authors</h1>
				<ul class="list-group">
				<?php 
				foreach($Project->authors as $author)
				{
					?>
					<li class="list-group-item">
					<?php echo $author->username; 
					if($NC_USERINFO['uid'] != $author->uid) {
					?>
					<a href="/scripts/project.php?id=<?php echo $Project->id; ?>&a=delauthor&uid=<?php echo $author->uid; ?>" class="btn btn-danger pull-right">Delete</a>
					<?php } ?>
					</li>
				<?php 
				}
				?>
				</ul>
				<form id="addauthor" class="form-inline" method="post" action="/scripts/project.php?id=<?php echo $Project->id; ?>&a=addauthor">
					<div class="form-group">
						<label for="authorusername">Username</label>
						<input type="text" class="form-control" id="authorusername" placeholder="Enter EXACT Username" name="uname">
					</div>
					<input type="submit" class="btn btn-primary" value="Add Author">
				</form>
			</div>
			<div class="col-md-6">
				<h1>Add/Update Files</h1>
				<form id="fileUploads" class="dropzone" action="/scripts/project.php?id=<?php echo $Project->id; ?>&a=addfile"></form>
			</div>
		</div>
	</div>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/resources/footer.php"); ?>

	<script src="/scripts/dropzone.js"></script>
	<script src="/scripts/project_edit.js"></script>
	<script>
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
</script>

</body>