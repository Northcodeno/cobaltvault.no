<?php require("resources/prehead.php"); ?>
<?php 
function drawForm($id)
{
    global $NC_LOGGED_IN,$NC_USERINFO;
	if($NC_LOGGED_IN)
    if($NC_USERINFO['rank'] > 7)
	{
	?>
	<br/><br/>
	<form action="scripts/postfaq.php?id=<?php echo $id; ?>" method="post" class="form">
	    <input type="text" name="title" class="form-control" placeholder="Title" /><br/>
	    <textarea name="content" class="form-control tinymce">Content</textarea><br/>
	    <input type="submit" class="form-control" value="Post"/>
	</form>
	<?php
	}
}

function drawPanel($id,$row)
{
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion_<?php echo $id; ?>" href="#collapse_<?php echo $id; ?>_<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
            </h4>
        </div>
        <div id="collapse_<?php echo $id; ?>_<?php echo $row['id']; ?>" class="panel-collapse collapse">
            <div class="panel-body">
                <?php echo $row['content']; ?>
            </div>
        </div>
    </div>
    <?php
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Cobalt Vault | FAQ</title>
    <?php include_once("resources/head.php"); ?>
  </head>
  <body>
    <?php include("resources/header.php"); ?>
    <div class="container">
    	<div class="col-md-4">
    		<h2>Cobalt Vault</h2>
    		<div class="panel-group" id="accordion_cv">
    		<?php 
    		$q = "SELECT * FROM faq WHERE cat = 0";
    		$res = $mysql->query($q);
    		while($row = $res->fetch_array())
    		{
    			drawPanel("cv",$row);
            } 
            ?>
    		</div>
    		<?php drawForm(0); ?>
    	</div>
    	<div class="col-md-4">
    		<h2>Installation</h2>
    		<div class="panel-group" id="accordion_inst">
    		<?php 
    		$q = "SELECT * FROM faq WHERE cat = 1";
    		$res = $mysql->query($q);
    		while($row = $res->fetch_array())
    		{
    		drawPanel("inst",$row);
            } 
            ?>
    		</div>
    		<?php drawForm(1); ?>
    	</div>
    	<div class="col-md-4">
    		<h2>About</h2>
    		<p>
    		<?php 
    		$q = "SELECT * FROM faq WHERE cat = 2";
    		$res = $mysql->query($q);
    		$data = $res->fetch_assoc();
            if($NC_LOGGED_IN)
            {
            if($NC_USERINFO['rank'] > 7)
            {
    			?>
    			<form method="post" action="scripts/updatefaq.php">
    			<textarea class="form-control tinymce" name="content"><?php echo $data['content']; ?></textarea><br/>
    			<input type="submit" class="form-control" value="Update"/>
    			</form>
    			<?php
            }
    		}else{
    		echo $data['content'];
    		}
    		 ?>
    		</p>
    	</div>
    </div>
    <div class="container">
        <?php include("resources/footer.php"); ?>
    </div>
  </body>
</html>