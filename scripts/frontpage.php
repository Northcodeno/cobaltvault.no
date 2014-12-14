<?php
function thumbnail($p)
{
    ?>
    <div class="thumbnail">
        <a href='/project/<?php echo $p->idname; ?>'><img class="t" src="<?php echo $p->thumbnail_url; ?>" alt="<?php echo $p->name; ?>"></a>
        <h3><a href='/project/<?php echo $p->idname; ?>'><?php echo $p->name; ?></a></h3>
        <p>
            Made by: <?php echo $p->prettyAuthors(); ?><br/>
            <?php echo $p->shortDesc(); ?>
        </p>
    </div>
    <?php
}

function listMaps($order)
{
    $Projects = Project::getProjects("projects.public = '1' AND projects.thumbnail_url != '' AND projects.thumbnail_url IS NOT NULL",$order,3);
    foreach($Projects as $p)
        thumbnail($p);
}

function news()
{
    global $NC_LOGGED_IN,$NC_USERINFO,$mysql;
    ?>
    <h2>News</h2>
    <?php
    if($NC_LOGGED_IN)
    {
        if($NC_USERINFO['rank'] > 7 )
        {
            ?>
            <form action="/scripts/postnews.php" method="post" class="form">
                <input type="text" name="title" class="form-control" placeholder="Title" /><br/>
                <textarea name="text" class="form-control tinymce">Content</textarea><br/>
                <input type="submit" class="form-control" value="Post"/>
            </form><br/>
            <?php
        }
    }
    $query = "SELECT * FROM news ORDER BY id DESC LIMIT 3";
    $r = $mysql->query($query);
    ?>
    <div class="panel-group" id="accordion_news">
        <?php
        $first = true;
        while($row = $r->fetch_array())
        {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion_news" href="#collapse_news_<?php echo $row['id']; ?>">
                            <?php echo $row['title']; ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse_news_<?php echo $row['id']; ?>" class="panel-collapse collapse <?php if($first) { echo 'in'; } ?>">
                    <div class="panel-body">
                        <?php echo $row['text']; ?>
                    </div>
                </div>
            </div>
            <?php
            $first = false;
        }
        ?>
    </div>
    <?php
}
function toDo()
{
    ?>
    <br/>
    <div class="panel-group" id="accordion_todo">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion_todo" href="#collapse_ToDo">
                        <b>To-Do List</b>
                    </a>
                </h4>
            </div>
            <div id="collapse_ToDo" class="panel-collapse collapse in">
                <div class="panel-body">
                    <ol>
                        <li>Multiple Authors</li>
                        <li>Better Map Uploading system</li>
                        <li>Custom Soundtrack Integration</li>
                        <li>API (for applications)</li>
                        <li>More...</li>
                    </ol>
                    <b>Maybe in the future...</b>
                    <ul>
                        <li>Google+ Integration</li>
                        <li>Mutliple version handling</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
}
function carousel($i,$order = "downloads DESC")
{
    $Projects = Project::getProjects("
    public = '1' 
    AND thumbnail_url != '' 
    AND thumbnail_url IS NOT NULL 
    "//AND date_modified > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 28 DAY)"
    , $order, $i);
    $i = sizeof($Projects);
    ?>
    <div id="carousel-maps" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-maps" data-slide-to="0" class="active"></li>
            <?php
            for($j = 1; $j < $i; $j++)
            {
                ?><li data-target="#carousel-maps" data-slide-to="<?php echo $j; ?>"></li>
                <?php
            }
            ?>
        </ol>
        <!-- Wrapper for slides -->
        <div class="aspectwrapper">
            <div class="carousel-inner aspectwrapper-inner">
                <?php
                $first = true;
                foreach($Projects as $p)
                {
                    
                    ?>
                    <div class="item <?php if($first) echo 'active'; ?>">
                        <a href="/project/<?php echo $p->idname; ?>"><img src="<?php echo $p->thumbnail_url; ?>" alt="<?php echo $p->name; ?>"></a>
                        <div class="carousel-caption">
                            <h3><a href="/project/<?php echo $p->idname; ?>"><?php echo $p->name; ?></a></h3>
                        </div>
                    </div>
                    <?php
                    $first = false;
                }
                ?>
            </div>
            <a class="left carousel-control" href="#carousel-maps" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-maps" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
    </div>
    <?php
}

function stream()
{
    //if(!isset($_GET['s']))
    //    return 0;
    global $mysql;
    $stmt = $mysql->prepare("SELECT id,username,display_name FROM streams");
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows < 1)
    {
        $stmt->close();
        return 0;
    }
    $stmt->bind_result($id,$username,$display_name);
    $stmt->fetch();
    $stmt->close();

    ?>
    <h2><a href="http://www.twitch.tv/<?php echo $username; ?>"><?php echo $display_name; ?> is currently streaming Cobalt!</h2></a>
    <div class="aspectwrapper">
        <div class="aspectwrapper-inner" id="stream">
            <object 
                type="application/x-shockwave-flash"
                id="live_embed_player_flash" 
                data="http://www.twitch.tv/widgets/live_embed_player.swf?channel=<?php echo $username; ?>" 
                bgcolor="#000000">
                    <param name="allowFullScreen" value="true" />
                    <param name="allowScriptAccess" value="always" />
                    <param name="allowNetworking" value="all" />
                    <param name="movie" value="http://www.twitch.tv/widgets/live_embed_player.swf" />
                    <param name="flashvars" value="hostname=www.twitch.tv&channel=<?php echo $username; ?>&auto_play=true&start_volume=25" />
            </object>
        </div>
    </div>

    <script>
    $(document).ready(function(){

    $("#live_embed_player_flash").width($("#stream").width());
    $("#live_embed_player_flash").height($("#stream").height());

    });
    </script>


    <?php return 1;

}
?>