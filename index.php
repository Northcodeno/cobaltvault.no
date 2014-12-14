<?php 
require("resources/prehead.php");
require_once("scripts/frontpage.php");
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cobalt Vault</title>
        <meta name="author" content="Northcode">
        <meta name="description" content="Download Cobalt Maps and Mods at a central place or share your own creations!">
        <?php include_once("resources/head.php"); ?>
        <link rel="stylesheet" href="style/index.css">
        <script src="scripts/indexs.js"></script>
    </head>
    <body>
        <?php include("resources/header.php"); ?>        
        <div class="container">
            <div class="col-md-8">
                <?php if(!stream()) { ?>
                <h2>Featured (WIP)</h2>
                <?php
                carousel(4,"rating");
                ?>
                <?php } ?>
                <div class="well" style="margin-top:10px;">
                    <p>Upload your map today!</p>
                    <a <?php if($NC_LOGGED_IN) echo 'href="/create"'; else echo 'href="#modal_login" data-toggle="modal"'; ?> class="btn btn-primary">Create Project</a>

                </div>
                <div class="col-md-6">
                    <h2><a href="projectlist.php?order=uploaded">Latest</a></h2>
                    <?php
                    listMaps("projects.date_created DESC");
                    ?>
                </div>
                <div class="col-md-6">
                    <h2><a href="projectlist.php?order=downloads">Most Downloaded</a></h2>
                    <?php
                    listMaps("projects.downloads DESC");
                    ?>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                news();
                toDo();
                ?>
            </div>
        </div>
        <div class="container">
            <?php include("resources/footer.php"); ?>
        </div>
        <div style="height:300px;"></div>
    </body>
</html>