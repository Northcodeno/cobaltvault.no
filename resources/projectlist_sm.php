<?php
// Query and stuff
$page;
if(!isset($_GET['page']))
	$page = 1;
else
	$page = $_GET['page'];

$r_all = $mysql->query("SELECT projects.*,projects_maptypes.name AS maptype
FROM projects 
LEFT JOIN projects_maptypes ON projects.type = projects_maptypes.id
WHERE projects.public = '1' 
$add
ORDER BY $order $order_d");

$count = $r_all->num_rows;

$last_page = ceil($count / ITEMS_PER_PAGE_S) ;

pagination($page,$last_page);

$limit = ($page - 1) * ITEMS_PER_PAGE_S . ',' . ITEMS_PER_PAGE_S;

// THA ALMIGHTY QUERY!!!!

$Projects = Project::getProjects("projects.public = '1' $add","$order $order_d",$limit);
$i = 0;
$items_per_col = ITEMS_PER_PAGE_S / 3;
?>
<div>

<?php



foreach($Projects as $p) // Data loop
{
	if($i == 0)
	{
		?><div class="col-sm-4"><?php
	}

	?>

    <div class="thumbnail">
        <?php if($p->thumbnail_url && $p->thumbnail_url != "") { ?><a href='/project/<?php echo $p->idname; ?>'><img src="<?php echo $p->thumbnail_url; ?>" alt="<?php echo $p->name; ?>"></a><?php } ?>
        <h3><a href='project/<?php echo $p->idname; ?>'><?php echo $p->name; ?></a></h3>
        <h4><?php echo $p->typename; ?></h4>
        <p>
        	<?php echo shorten(strip_tags($p->desc),200,"..."); ?><br/>
            Made by: <?php echo $p->prettyAuthors(); ?>
        </p>
    </div>

    <?php
    $i++;
	if($i == $items_per_col)
	{
		echo "</div>";
		$i = 0;
	}
}
?>
</div>
<?php
pagination($page,$last_page); 
?>