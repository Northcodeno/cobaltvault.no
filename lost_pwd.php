<?php require("resources/prehead.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Cobalt Vault | Lost Password</title>
    <?php include_once("resources/head.php"); ?>
  </head>
  <body>
    <?php include("resources/header.php"); ?>
    <div class="container">
      <h1>Lost Password</h1>
      <form class="form-inline" method="post" action="scripts/lost_pwd.php">
        <div class="form-group">
          <input type="text" class="form-control" id="name" name="name" placeholder="Username/E-Mail">
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-default">Submit</button>
        </div>
      </form>
    </div>
      <?php include("resources/footer.php"); ?>
  </body>
</html>