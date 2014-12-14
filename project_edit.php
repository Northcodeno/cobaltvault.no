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
			</div>
			<div class="col-md-6">
				<h1>Add/Update Files</h1>
				<form id="fileUploads" class="dropzone" action="/scripts/project.php?id=<?php echo $Project->id; ?>&a=addfile"></form>
			</div>
		</div>
	</div>
	<div class="container">
		<?php include($_SERVER['DOCUMENT_ROOT'] . "/resources/footer.php"); ?>
	</div>

	<script src="/scripts/dropzone.js"></script>
	<script src="/scripts/project_edit.js"></script>

</body>