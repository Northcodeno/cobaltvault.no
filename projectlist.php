<?php 
require("resources/prehead.php"); 
require_once("lib/lib.php");
require_once("resources/projectlist.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>List | Cobalt Vault</title>
	<?php include_once("resources/head.php"); ?>
	<link rel="stylesheet" href="/style/projectlist.css">
	<link rel="stylesheet" href="/style/star.css">
</head>
<body>
	<?php include("resources/header.php"); ?>
	<br/>
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if(!isset($_GET['type'])) { echo 'class="active"'; } ?>><a href="/list">All Maps</a></li>
			<?php
			$res = $mysql->query("SELECT * FROM projects_maptypes ORDER BY id");
			while($row = $res->fetch_array())
			{
			$n_res = $mysql->query("SELECT * FROM projects WHERE type = '".$row['id']."' AND public = '1'");
			?>
			<li <?php if(isset($_GET['type']) && $_GET['type'] == $row['id']) { echo 'class="active"'; } ?>><a href="<?php echo getGETLinks(array("type" => $row['id'], "page" => "1")); ?>"><?php echo $row['name']." (".$n_res->num_rows.")"; ?></a></li>
			<?php 
			} 
			?>
		</ul>
	</div>
	<div class="col-md-10 col-sm-12">
		<div class="hidden-sm hidden-xs">
			<?php include("resources/projectlist_big.php"); ?>
		</div>

		<div class="visible-sm visible-xs">
			<?php include("resources/projectlist_sm.php"); ?>
		</div>
	</div>
    <div class="container">
    	<?php include("resources/footer.php"); ?>
    </div>
</body>
</html>