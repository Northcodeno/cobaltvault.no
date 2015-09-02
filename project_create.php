<?php require("resources/prehead.php"); 

if(!$NC_LOGGED_IN)
	alert("You have to be logged in to create a project!","warning","/");

?>
<!DOCTYPE html>
<html>
<head>
	<title>Create New Project | Cobalt Vault</title>
	<?php include_once("resources/head.php"); ?>
	<script src="scripts/project_create.js"></script>
</head>
<body>
	<?php include("resources/header.php"); ?>
	<div class="container">
		<h1>Create new Project</h1>
		<div class="row">
		<div class="col-sm-2"></div><div class="col-sm-8">
		<div class="alert alert-warning" style="font-size:17px;">
		<p><b>Read before creating a project!</b></p>
		<p>Fill in all the fields then hit Create.</p>
		<p>After the project has been created you will be taken to the project management page. Drop all your map/localization/music files into the upload dialog and hit the "Back to project" button.</p>
		<p>The last step is to make the project public so it will show up on the list. If you've done that you're done!</p>
		<p><i>If you forgot to make your project public you can find it on <?php echo $NC_USERINFO['username']; ?> > My Projects</i></p>
		</div>
		</div></div><br>
		<form method="post" action="scripts/project.php?a=create" class="form-horizontal" role="form" enctype="multipart/form-data">
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="name" name="name" placeholder="Name" data-check="true">
					<p class="help-block">This name will show up on the Front-Page/Projectlist/etc.   [&lt; and &gt; are NOT allowed!]</p>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">ID Name</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="idname" name="idname" placeholder="idname" data-check="true">
					<p class="help-block">This name will show in the URL of your project. (Only lower case letters [a-z])</p>
				</div>
			</div>
			<div class="form-group">
				<label for="type" class="col-sm-2 control-label">Type</label>
				<div class="col-sm-8">
					<select name="type" class="form-control">
						<?php
						$res = $mysql->query("SELECT * FROM projects_maptypes ORDER BY id ASC");
						while($row = $res->fetch_array())
						{
							echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
						} 
						?>
					</select>
					<p class="help-block">Choose your map type.</p>
				</div>
			</div>
			<div class="form-group">
				<label for="desc" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-8">
					<textarea type="description" class="form-control tinymce" id="desc" name="desc" data-check="true"></textarea>
					<p class="help-block">Provide as much description as possible. The first image in the description will be the thumbnail (host it at imgur or another image sharing site [we do not host images]).<br>Your project will only show up on the frontpage if it has an image!</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Create</button>
				</div>
			</div>
		</form>
	</div>
		<?php include("resources/footer.php"); ?>

	<script src="scripts/createprj.js"></script>
</body>
</html>