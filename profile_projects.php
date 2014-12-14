<?php require("resources/prehead.php"); 
if(!$NC_LOGGED_IN)
	alert("You have to be logged in to view your projects!","warning","index.php");
$uid = $NC_USERINFO['uid'];


$Projects = Project::getProjects("projects.author = $uid");

?>
<!DOCTYPE html>
<html>
  <head>
    <title>My Projects | Cobalt Vault</title>
    <?php include_once("resources/head.php"); ?>
    <style>
    .notpublic {
    	background-color:#FFEEEE;
    }
    </style>
  </head>
  <body>
    <?php include("resources/header.php"); ?>
    <div class="container"><br/>
    	<table class="table">
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Downloads</th>
			<th>Uploaded</th>
			<th>Last Updated</th>
		</tr>
		<?php

		foreach($Projects as $P) // Data loop
		{
			?>
			<tr <?php 
			if($P->public == 0)
				echo "class='notpublic'";
			?>>
				<td><a href="project/<?php echo $P->idname; ?>"><?php 
					echo $P->name."<br/></a>".
					$P->shortDesc();
				
				?></td>
				<td><?php echo $P->type; ?></td>
				<td><?php echo $P->downloads; ?></td>
				<td><?php echo $P->date_created; ?></td>
				<td><?php echo $P->date_modified; ?></td>
			</tr>
			<?php
		}
		?>
		</table>
    </div>
    <div class="container">
    	<?php include("resources/footer.php"); ?>
    </div>
  </body>
</html>