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
if($Project->isAuthor($NC_USERINFO['uid']))
	$owner = 1;
elseif(!$Project->public)
	alert("This project is hidden!");

if(isset($_POST['debug']))
{
echo "<pre>";
	var_dump($Project);
echo "</pre>";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $Project->name; ?> | Cobalt Vault</title>
	<meta name="author" content="<?php echo $Project->prettyAuthors(); ?>">
	<meta name="description" content="<?php echo $Project->shortDesc(); ?>">
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/resources/head.php"); include($_SERVER['DOCUMENT_ROOT'] . "/resources/project_head.php"); ?>
	<style>
	.comment-footer{
		color: #999; font-style: italic;
	}
	.map-dl a:hover{
		text-decoration: none;
	}
	</style>
	<script>
	tinymce.init({
		selector: ".tinymce-big",
		height: 600,
		plugins: [
		"advlist autolink lists link image charmap print preview anchor",
		"searchreplace visualblocks code",
		"insertdatetime media table contextmenu paste"
		],
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	});
	</script>
	<link rel="stylesheet" href="/style/project.css">
	<link rel="stylesheet" href="/style/star.css">
</head>
<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/resources/header.php"); ?>

	<?php if($owner) { ?>
	<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog" aria-labelledby="Are you sure?" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                <h4 class="modal-title">Are you sure?</h4>
	            </div>
	            <div class="modal-body">
	            Do you really want to delete your map? <b>This action is irreversible!</b>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                <a href="/project/<?php echo $Project->id; ?>/alter/delete" class="btn btn-danger">Delete Map</a>
	            </div>
	        </div>
	    </div>
	</div>
	<?php } ?>

	<div class="container">
		<?php
		if($edit)
			echo "<form method='post' action='/project/" . $Project->id . "/alter/edit'>";
		?>
		<h1>
			<?php 
			if($edit)
				echo '<input type="text" name="name" class="form-control" value="'.$Project->name.'"/>';
			else
				echo $Project->name; 
			?>
		</h1>
		<div class="col-md-9">

			<?php 
			if($edit)
				echo "<textarea class='form-control tinymce-big' height='500px' name='desc'>".$Project->desc."</textarea>";
			else
				echo $Project->desc;
			?>
		</div>
		<div class="col-md-3">
			<!-- Project Download -->
			<ul class="nav nav-pills nav-stacked">
				<div class="btn-group btn-block">
					<li><a href="<?php if(0) echo ADFLY; ?>/download/project/<?php echo $Project->id; ?>" class="btn btn-primary btn-block">Download Project</a></li>
					<?php if($owner) { ?><li><a href="/project/<?php echo $Project->idname; ?>/manage" class="btn btn-warning btn-block">Manage Project</a></li><?php } ?>
				</div>
			</ul><br>
			<!-- Map Collapse -->
			<div class="panel-group map-dl" id="accordion_maps">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion_maps" href="#collapse_maps">
								Files <span class="caret"></span>
							</a>
						</h4>
					</div>
					<div id="collapse_maps" class="panel-collapse collapse">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
								<div class="btn-group btn-block">
									<?php
									foreach($Project->files as $file)
									{
										?><li><a href="/download/file/<?php echo $file->id; ?>" class="btn btn-info btn-block">Download <?php echo $file->filename; ?></a></li><?php
									}
									?>
								</div>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<br/>
			<div class="panel panel-default">
				<div class="panel-heading">Info</div>
				<table class="table table-bordered">
					<tr>
						<td>Date Created</td>
						<td><?php echo $Project->date_created; ?></td>
					</tr>
					<tr>
						<td>Map Type</td>
						<td>
						<?php 
						if($edit)
						{
							echo '<select name="type" class="form-control">';
							$type_res = $mysql->query("SELECT * FROM projects_maptypes");
							while($type_row = $type_res->fetch_array())
							{
								$type_add = "";
								if($type_row["id"] == $Project->type)
									$type_add = "selected";
								echo '<option '.$type_add.' value="'.$type_row["id"].'">'.$type_row['name'].'</option>';
							}
							echo '</select>';
						} else {
							echo $Project->typename; 
						}
						?>
						</td>
					</tr>
					<tr>
						<td>Last Update</td>
						<td><?php echo $Project->date_modified; ?></td>
					</tr>
					<tr>
						<td>Rating</td>
						<td>
							<div class="starRate">
								<?php 
								$rating = $Project->rating;
								$rating_r = intval($Project->rating);

								?>
								<div>Rating: <?php echo number_format($Project->rating,1); ?><b></b></div>
								<ul>
									<li><a href="/project/<?php echo $Project->id; ?>/alter/rate/5"><span>Rate 5</span><?php if($rating_r == 5) { echo "<b></b>"; } ?></a></li>
									<li><a href="/project/<?php echo $Project->id; ?>/alter/rate/4"><span>Rate 4</span><?php if($rating_r == 4) { echo "<b></b>"; } ?></a></li>
									<li><a href="/project/<?php echo $Project->id; ?>/alter/rate/3"><span>Rate 3</span><?php if($rating_r == 3) { echo "<b></b>"; } ?></a></li>
									<li><a href="/project/<?php echo $Project->id; ?>/alter/rate/2"><span>Rate 2</span><?php if($rating_r == 2) { echo "<b></b>"; } ?></a></li>
									<li><a href="/project/<?php echo $Project->id; ?>/alter/rate/1"><span>Rate 1</span><?php if($rating_r == 1) { echo "<b></b>"; } ?></a></li>
								</ul>
							</div>
							<?php /*echo $Project['rating_count']; ?> rating<?php if($Project['rating_count'] > 1 || $Project['rating_count'] == 0) echo "s"; */ ?>
						</td>
					</tr>
					<!--<tr>
						<td>Version</td>
						<td>
						<?php /*
						if($edit)
							echo '<input type="text" name="ver" class="form-control" value="'.$Project['version'].'"/>';
						else
							echo $Project->version; 
						*/ ?>

						</td>
					</tr>-->
					<tr>
						<td>Downloads</td>
						<td><?php echo $Project->downloads; ?></td>
					</tr>
					<tr>
						<td>Authors</td>
						<td><?php echo $Project->prettyAuthors("<br />"); ?></td>
					</tr>
				</table>
			</div>
			<ul class="nav nav-pills nav-stacked">
				<div class="btn-group btn-block">
				<?php if($owner && !$edit) { ?>
				<li><a class="btn btn-primary btn-block" href="/project/<?php echo $Project->id; ?>/edit">Edit Description</a></li>
				<?php if($Project->public == 1) { ?>
				<li><a class="btn btn-warning btn-block" href="/project/<?php echo $Project->id; ?>/alter/private">Hide Project</a></li>
				<?php } else { ?>
				<li><a class="btn btn-success btn-block" href="/project/<?php echo $Project->id; ?>/alter/public">Show Project</a></li>
				<?php } ?>
				<li><a href="#modal_delete" data-toggle="modal" class="btn btn-danger btn-block">Delete</a></li>
				<?php } if($edit) { ?>
				<li><input type="submit" value="Save" class="btn btn-success btn-block" /></li>
				<?php }?>
				</div>
			</ul>
		</div>
		<?php
		if($edit)
			echo "</form>";
		?>
	</div>

	<div class="container">
		<h1>Comments</h1>
		<div class="col-md-9">
			<?php
			if($NC_LOGGED_IN)
			{
				?>
				<form action="/scripts/project.php?id=<?php echo $id; ?>&a=comment" method="post" class="form" role="form">
					<br><textarea class="tinymce" name="text"></textarea><br/>
					<input type="submit" class="btn btn-primary" value="Submit comment" />
				</form>

				<?php
			}


		$Project->displayComments();
		?>
		</div>
	</div>
    <div class="container" style="margin-top:100px;">
        <?php include("resources/footer.php"); ?>
    </div>
	<div style="padding:200px;"></div>
	<script>
		
		$("a.reply-button").click(function(event) {
			$(this).parent().find('.reply-container').first().attr('style','');
			$(this).parent().find('.reply-text').first().attr('class','active-reply');
			tinymce.init({selector:'.active-reply'});
		});
	</script>
</body>
</html>
