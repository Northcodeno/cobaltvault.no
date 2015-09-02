<?php require("resources/prehead.php"); 
if(!$NC_LOGGED_IN)
	alert("You have to be logged in to view your projects!","warning","index.php");
$uid = $NC_USERINFO['uid'];

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
		// Query and stuff
		$query = "SELECT * FROM projects WHERE author = $uid ORDER BY public,date_modified DESC";
		$r = $mysql->query($query);

		while($row = $r->fetch_array()) // Data loop
		{
			?>
			<tr <?php 
			if($row['public'] == 0)
				echo "class='notpublic'";
			?>>
				<td><a href="project.php?id=<?php echo $row['id']; ?>"><?php 
					echo $row['name']."<br/></a>".
					substr(strip_tags($row['desc']),0,80)."...";
				
				?></td>
				<td><?php echo $row['type']; ?></td>
				<td><?php echo $row['downloads']; ?></td>
				<td><?php echo date("M d, Y",strtotime($row['date_created'])); ?></td>
				<td><?php echo date("M d, Y",strtotime($row['date_modified'])); ?></td>
			</tr>
			<?php
		}
		?>
		</table>
    </div>
	<?php include("resources/footer.php"); ?>
  </body>
</html>