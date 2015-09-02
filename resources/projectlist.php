<?php
define("ITEMS_PER_PAGE",10);
define("ITEMS_PER_PAGE_S",12);

function getGETLinks($array)
{
	$final = "/list";
	if(isset($array['order']))
		$final .= "/order/".$array['order'];
	elseif(isset($_GET['order']))
		$final .= "/order/".$_GET['order'];

	if(isset($array['author']))
		$final .= "/author/".$array['author'];
	elseif(isset($_GET['author']))
		$final .= "/author/".$_GET['author'];

	//echo $_GET['type'];

	if(isset($array['type']) && $array['type'] == "x")
		$final = $final;
	elseif(isset($array['type']))
		$final .= "/type/".$array['type'];
	elseif(isset($_GET['type']))
		$final .= "/type/".$_GET['type'];

	if(isset($array['page']))
		$final .= "/page/".$array['page'];
	elseif(isset($_GET['page']))
		$final .= "/page/".$_GET['page'];

	if(isset($array['search']))
		$final .= "/search/".$array['search'];
	elseif(isset($_GET['search']))
		if($_GET['search'] != "")
		$final .= "/search/".$_GET['search'];

	return str_replace(' ','+',$final);
}

function pagination($page,$last_page)
{
	?>
		<ul class="pagination">
			<li <?php if($page == 1) { echo 'class="disabled"'; } ?>><a href="<?php if($page != 1) { echo getGETLinks(array("page" => 1)); } else { echo "#"; } ?>">&laquo;</a></li>
			<li <?php if($page == 1) { echo 'class="disabled"'; } ?>><a href="<?php if($page != 1) { echo getGETLinks(array("page" => $page - 1)); } else { echo "#"; } ?>">&lsaquo;</a></li>
			<?php if ($page > 2) { ?><li><a href="<?php echo getGETLinks(array("page" => $page -2)); ?>"><?php echo $page - 2; ?></a></li><?php } ?>
			<?php if ($page > 1) { ?><li><a href="<?php echo getGETLinks(array("page" => $page -1)); ?>"><?php echo $page - 1; ?></a></li><?php } ?>
			<li class="active"><a href="#"><?php echo $page; ?> <span class="sr-only">(current)</span></a></li>
			<?php if ($page < $last_page) { ?><li><a href="<?php echo getGETLinks(array("page" => $page +1)); ?>"><?php echo $page + 1; ?></a></li><?php } ?>
			<?php if ($page < $last_page-1) { ?><li><a href="<?php echo getGETLinks(array("page" => $page +2)); ?>"><?php echo $page + 2; ?></a></li><?php } ?>
			<li <?php if($page == $last_page) { echo 'class="disabled"'; } ?>><a href="<?php if($page != $last_page) { echo getGETLinks(array("page" => $page + 1)); } else { echo "#"; } ?>">&rsaquo;</a></li>
			<li <?php if($page == $last_page) { echo 'class="disabled"'; } ?>><a href="<?php if($page != $last_page) { echo getGETLinks(array("page" => $last_page)); } else { echo "#"; } ?>">&raquo;</a></li>
		</ul>
	<?php
}

$add = "";
$order = "projects.date_modified";


if(isset($_GET['type']) && $_GET['type'] != "")
	$add .= "AND projects.type = '".$_GET['type']."' ";
if(isset($_GET['author']) && $_GET['author'] != "")
	$add .= "AND projects.author = '".$_GET['author']."' ";
if(isset($_GET['search']))
	$add .= "AND (projects.name LIKE '%".$_GET['search']."%' OR projects.desc LIKE '%".$_GET['search']."%') ";

/// Order Check
$order_d = "DESC";
if(isset($_GET['order']))
{
	switch ($_GET['order'])
	{
	case "name":
		$order = "projects.name";
		$order_d = "ASC";
		break;
	case "type":
		$order = "projects.type";
		$order_d = "ASC";
		break;
	case "author":
		$order = "projects.author";
		$order_d = "ASC";
		break;
	case "downloads":
		$order = "projects.downloads";
		$order_d = "DESC";
		break;
	case "uploaded":
		$order = "projects.date_created";
		$order_d = "DESC";
		break;
	case "rating":
		$order = "projects.rating";
		$order_d = "DESC";
		break;
	default:
		$order = "projects.date_modified";
		$order_d = "DESC";
		break;
	}
}

?>