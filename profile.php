<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
define("DIR","files/pimages/");

require("resources/prehead.php"); 
require_once("resources/connect.php");
require_once("lib/lib.php");

$id;
if(isset($_GET['id']) && $_GET['id'] != "")
  $id = $_GET['id'];
else
  _error("Invalid UID");


// USER FETCH $PROFILE
$PROFILE = nc_api::get_user_info($id);
if(!(isset($PROFILE['username'])))
  header("Location: index.php");

$pimage = "images/profile.png";
if(file_exists(DIR . $PROFILE['username'] . ".png"))
  $pimage = DIR . $PROFILE['username'] . ".png";
if(file_exists(DIR . $PROFILE['username'] . ".jpg"))
  $pimage = DIR . $PROFILE['username'] . ".jpg";

// Fetch Projects
$Projects = Project::getProjects("author = " . $PROFILE['uid'] . " AND public = '1'");

$c_query = "SELECT * FROM comments WHERE author = '$id'";
$c_r = $mysql->query($c_query);


$owner = $NC_LOGGED_IN && $NC_USERINFO['uid'] == $id;

$edit = isset($_GET['edit']);

?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $PROFILE['username']; ?> | Cobalt Vault</title>
    <?php include_once("resources/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="/style/profile.css">
  </head>
  <body>
    <?php include("resources/header.php"); ?>
    <div class="container">
      <div class="col-md-3 col-sm-3">
        <h3><?php
        echo $PROFILE['username'];
        ?></h3>
        <img src="/<?php echo $pimage; ?>" class="img-thumbnail pimage" />
        <?php
        if($owner)
        {
          ?>
          <form method="post" action="/scripts/profile.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
            <input type="file" name="file" class="btn btn-default btn-block"/>
            <input type="submit" value="Update Image" class="btn btn-default btn-block"/>
          </form>
          <?
        }
        ?>
        <table class="table">
          <tr>
            <td>Joined</td>
            <td><?php echo date("Y.m.d",strtotime($PROFILE['registered'])); ?></td>
          </tr>
          <tr>
            <td>Maps</td>
            <td><?php echo sizeof($Projects); ?></td>
          </tr>
          <!--<tr>
            <td>Avg. Rating</td>
            <td>{avg_r}</td>
          </tr>-->
        </table>
        <h4>About</h3>
        <p>
          <?php 
          if($owner && $edit)
          {
            ?>
            <form method="post" action="/scripts/profile.php?id=<?php echo $id; ?>">
            <textarea name="bio" class="form-control" style="height:300px;"><?php echo $PROFILE['info']; ?></textarea><br/>
            <input type="submit" value="Save" class="btn btn-default btn-block">
            </form>
            <?php
          } else {
          echo $PROFILE['info']; 
          if($owner) {
            echo '<br/><a href="?id='.$_GET['id'].'&edit" class="btn btn-default btn-block">Edit</a>';
          }
          }
          ?>
        </p>
      </div>
      <div class="col-md-9 col-sm-9">
        <h3>Maps</h3>
        <table class="table table-striped">
          <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Downloads</th>
            <th>Uploaded</th>
            <th>Updated</th>
          </tr>

          <?php
          foreach($Projects as $P) {
          ?>

          <tr>
            <td><a href="/project/<?php echo $P->idname; ?>"><?php echo $P->name; ?><br/></a>
            <?php echo $P->shortDesc(); ?></td>
            <td><?php echo $P->type; ?></td>
            <td><?php echo $P->downloads; ?></td>
            <td><?php echo $P->date_created; ?></td>
            <td><?php echo $P->date_modified; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
    <div class="container">
      <?php include("resources/footer.php"); ?>
    </div>
  </body>
</html>