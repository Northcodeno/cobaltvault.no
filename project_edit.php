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
	<style>
	.loc {
		width: 100%;
		height: 200px;
	}
	</style>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/resources/header.php"); ?>

	<div class="container">
		<h1>Maps
		<form method="post" class="form-inline" role="form" action="/project/<?php echo $Project->id; ?>/alter/addmap">
			<div class="form-group">
				<input type="text" name="name" class="form-control">
			</div>
			<div class="form-group">
				<input type="submit" value="Add Map" class="form-control">
			</div>
		</form></h1>
		<?php 
		foreach($Project->maps as $Map) { 
			if($i == 2) $i = 0;
			if($i == 0) echo "<div class'row'>";
			?>
			<div class="<?php echo $coltype; ?>">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3><?php echo $Map->name; ?></h3>
					</div>
					<div class="panel-body">
						<form class="form-inline" role="form" method="post" enctype="multipart/form-data" action="/project/<?php echo $Project->id; ?>/alter/addfile/<?php echo $Map->id; ?>">
							<div class="form-group"><input type="file" name="file" class="form-control"></div>
							<div class="form-group"><input type="submit" class="form-control btn btn-primary" value="Add File"></div>
						</form>
						<h4>Files</h4>
						<?php foreach($Map->files as $File) { ?>
							<form role="form" method="post" enctype="multipart/form-data" action="/project/<?php echo $Project->id; ?>/alter/updatefile/<?php echo $Map->id . "/" . $File->id; ?>">
								<div class="form-group">
									<div class="form-inline">
										<div class="form-group">
											<p class="form-control-static"><b><?php echo $File->filename; ?></b></p>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="form-inline">
										<div class="form-group">
											<input type="file" class="form-control" name="file">
										</div>
										<div class="form-group">
											<input type="submit" class="form-control btn btn-warning" value="Update">
										</div>
										<div class="form-group">
											<a href="/project/<?php echo $Project->id; ?>/alter/delfile/<?php echo $Map->id . "/" . $File->id; ?>" class="form-control btn btn-danger">Delete</a>
										</div>
									</div>
								</div>
							</form>
						<?php } ?>

						<h4>Localization</h4>
						<?php if($loc = $Map->containsFileType(2)) { ?>
						<form method="post" action="/project/<?php echo $Project->id; ?>/alter/updateloc">
							<input type="hidden" name="file" value="<?php echo $loc->id; ?>">
							<div class="form-group"><textarea name="loc" class="loc form-control"><?php 
								$file = fopen($loc->getFile(),"r");
								echo fread($file, filesize($loc->getFile()));
								fclose($file);
							?></textarea></div>
							<div class="form-group"><input type="submit" class="btn btn-primary" value="Update Localization"></div>
						</form>
						<?php } else { ?>
						<form method="post" action="/project/<?php echo $Project->id; ?>/alter/addloc/<?php echo $Map->id; ?>">
							<div class="form-group"><textarea name="loc" class="loc form-control" rows="15"></textarea></div>
							<div class="form-group"><input type="submit" class="btn btn-primary" value="Add Localization"></div>
						</form>
						<?php } ?>

						<a href="/project/<?php echo $Project->id; ?>/alter/delmap/<?php echo $Map->id; ?>" class="btn btn-block btn-danger">Delete Project</a>
					</div>
				</div>
				
			</div>

			<?php 
			if($i == 0) echo "</div>";
			$i++;
		} 
		?>
	</div>
	<div class="container">
		<?php include($_SERVER['DOCUMENT_ROOT'] . "/resources/footer.php"); ?>
	</div>
</body>