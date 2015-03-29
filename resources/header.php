<?php
$_maps_r = $mysql->query("SELECT * FROM projects WHERE public = '1' ");
$maps_count = $_maps_r->num_rows;
?>

<div class="navbar navbar-fixed-top navbar-default" style="margin-bottom: 0px;">
    <div class="container">
		<div class="container-fluid">
            <div class="navbar-header">
    			<a href="/" class="navbar-brand">Cobalt Vault</a>
    			<a class="navbar-toggle" data-toggle="collapse" data-target="#header-collapse">
    				<span class="icon-bar"></span>
    				<span class="icon-bar"></span>
    				<span class="icon-bar"></span>
    			</a>
            </div>
			<div class="navbar-collapse collapse" id="header-collapse">
                <form method="get" class="navbar-form navbar-left" role="search" action="/list/">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search Maps..." name="search" value="<?php if(isset($_GET['search'])) { echo $_GET['search']; }?>">
                    </div>
                    <input type="submit" class="btn btn-default" value="Go">
                </form>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
					<li><a href="/list">Projects (<?php echo $maps_count; ?>)</a></li>
						<?php
					if($NC_LOGGED_IN)
					{
					?>
					<li class="dropdown">
                        <a href="#" data-toggle="dropdown"><?php echo $NC_USERINFO['username']; ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/user/<?php echo $NC_USERINFO['uid'] ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
                            <li><a href="/scripts/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                            <li><a href="/create"><span class="glyphicon glyphicon-cloud-upload"></span> Create Project</a></li>
                            <li><a href="/userprojects">My Projects</a></li>
                            <? /*<?php if ($_SESSION['rank'] > 8) { ?><li><a href="#modal_login" data-toggle="modal">Override Login</a></li><?php } ?>*/ ?>
                        </ul>
                    </li>
					<?php
					}
					else
					{
					?>
					<li><a href="#modal_login" data-toggle="modal">Login</a></li>
					<li><a href="http://dev.northcode.no/register.php">Register</a></li>
					<?php
					}
					?>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown">Help<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/faq">FAQ</a>
                            <li><a href="https://github.com/Jenjen1324/cobaltvault.no" target="_blank">GitHub</a></li>
                            <li class="divider"></li>
                            <li><a href="http://www.northcode.no/" target="_blank">Northcode</a></li>
                            <li><a href="http://www.cobaltforum.net/topic/2701-cobalt-vault-upload-and-share-your-maps/" target="_blank">CobaltForum Thread</a></li>
                            <li class="divider"></li>
                            <li><a href="http://playcobalt.com/" target="_blank">Cobalt</a></li>
                            <li><a href="http://oxeyegames.com/" target="_blank">Oxeye Games</a></li>
                        </ul>
                    </li>
						
				</ul>
			</div>
		</div>
    </div>
</div>


<div class="modal fade" id="modal_login" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<form method="post" class="form-horizontal" role="form" action="/scripts/login.php">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          		<h4 class="modal-title">Login</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username" class="col-lg-2 control-label">Username:</label>
                    <div class="col-lg-10"><input type="text" class="form-control" id="username" name="username" placeholder="Username" /></div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-lg-2 control-label">Password:</label>
                    <div class="col-lg-10"><input type="password" class="form-control" id="password" name="password" placeholder="Password"/></div>
                </div>
            </div>
            <div class="modal-footer">
                <!--<a href="lost_pwd.php" class="btn btn-danger">Lost Password</a>-->
            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            	<input type="submit" class="btn btn-primary" value="Login"/>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_register" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<form method="post" class="form-horizontal" role="form" action="/scripts/register.php">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          		<h4 class="modal-title">Register</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="r_username" class="col-lg-2 control-label">Username:</label>
                    <div class="col-lg-10"><input type="text" class="form-control" id="r_username" name="username" placeholder="Username" /></div>
                </div>
                <div class="form-group">
                    <label for="r_email" class="col-lg-2 control-label">E-Mail:</label>
                    <div class="col-lg-10"><input type="text" class="form-control" id="r_email" name="email" placeholder="E-Mail" /></div>
                </div>
                <div class="form-group">
                    <label for="r_password" class="col-lg-2 control-label">Password:</label>
                    <div class="col-lg-10"><input type="password" class="form-control" id="r_password" name="password" placeholder="Password"/></div>
                </div>
                <div class="form-group">
                		<label for="r_password_c" class="col-lg-2"></label>
                    <div class="col-lg-10"><input type="password" class="form-control" id="r_password_c" name="password_c" placeholder="Confirm Password"/></div>
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            	<input type="submit" class="btn btn-default" value="Register"/>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_notification" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Notification</h4>
            </div>
            <div class="modal-body">
            <?php
            if(isset($_GET['m']))
                echo stripslashes($_GET['m']);
            if(isset($_POST['m']))
                echo decrypt(stripslashes($_POST['m']));
            ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_GET['m']) || isset($_POST['m']))
{
    ?>
    <script>
    $(document).ready(function() {
        $("#modal_notification").modal();
    });
    </script>
    <?php
}

?>


<div class="container">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- CobaltVault -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3288274218040596"
     data-ad-slot="2273912861"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>