<?php
// Query and stuff
$page;
if(!isset($_GET['page']))
	$page = 1;
else
	$page = $_GET['page'];

$query_all = "SELECT projects.*
FROM projects 
WHERE projects.public = '1' 
$add
ORDER BY $order $order_d";

$r_all = $mysql->query($query_all);

echo $mysql->error;

$count = $r_all->num_rows;

$last_page = ceil($count / ITEMS_PER_PAGE) ;

pagination($page,$last_page);


function printRating($val)
{
	if(!$val)
		echo '-';
	else
		echo $val;
	/*
	?>
	<div class="starRatePassive">
	<?php
	//echo $val;
	$rating_r = round($val);
	if(!($val > 0))
		$val = "-";
	else
		$val = number_format($val,1);
	?>
	<div><!--Rating: <?php echo $val; ?> --><b></b></div>
	<ul>
		<li><a href="#"><?php if($rating_r == 5) { echo "<b></b>"; } ?></a></li>
		<li><a href="#"><?php if($rating_r == 4) { echo "<b></b>"; } ?></a></li>
		<li><a href="#"><?php if($rating_r == 3) { echo "<b></b>"; } ?></a></li>
		<li><a href="#"><?php if($rating_r == 2) { echo "<b></b>"; } ?></a></li>
		<li><a href="#"><?php if($rating_r == 1) { echo "<b></b>"; } ?></a></li>
	</ul>
	</div>
	<?php echo $val; ?>
	<?php
	*/
}

?>


<table class="table table-striped">
<thead>
<tr>
	<th><a href="<?php echo getGETLinks(array("order" => "name")); ?>">Name</a></th>
	<th></th>
	<th><a href="<?php echo getGETLinks(array("order" => "type")); ?>">Type</a></th>
	<th>Author</th>
	<th><a href="<?php echo getGETLinks(array("order" => "rating")); ?>">Rating</a></th>
	<th><a href="<?php echo getGETLinks(array("order" => "downloads")); ?>">Downloads</a></th>
	<th><a href="<?php echo getGETLinks(array("order" => "uploaded")); ?>">Uploaded</a></th>
	<th><a href="<?php echo getGETLinks(array("order" => "updated")); ?>">Updated</a></th>
	<th><span class="glyphicon glyphicon-cloud-download"></span></th>
</tr>
</thead>
<tbody>
<?php

$limit = ($page - 1) * ITEMS_PER_PAGE . ',' . ITEMS_PER_PAGE;

// THA ALMIGHTY QUERY!!!!

$Projects = Project::getProjects("projects.public = '1'" . $add, $order . " " . $order_d . ", projects.date_modified", $limit );

foreach($Projects as $P) // Data loop
{
	?>
	<tr>
		<td class="c_thumbnail"><?php if($P->thumbnail_url && $P->thumbnail_url != "") { ?><a href="/project/<?php echo $P->idname; ?>"><img src="<?php echo $P->thumbnail_url; ?>" alt="<?php echo $P->name; ?>"></a><?php } ?></td>
		<td><a href="/project/<?php echo $P->idname; ?>"><b><?php 
			echo $P->name."</b><br/></a>".
			shorten(strip_tags($P->desc),80,"...");
		
		?></td>
		<td><?php echo $P->typename; ?></td>
		<td><?php echo $P->prettyAuthors(); ?></td>
		<td style="width:80px;"><?php printRating($P->rating); ?></td>
		<td><?php echo $P->downloads; ?></td>
		<td><?php echo date("M d, Y",strtotime($P->date_created)); ?></td>
		<td><?php echo date("M d, Y",strtotime($P->date_modified)); ?></td>
		<td><a class="btn btn-primary" href="/download/project/<?php echo $P->id; ?>"><span class="glyphicon glyphicon-cloud-download"></span></a></td>
	</tr>
	<?php
}
?>
</tbody>
</table>

<?php pagination($page,$last_page); ?>