<?php
/**
 * Data for the repeater on the projectlist
 **/

header("Content-Type: application/json");

require_once($_SERVER['DOCUMENT_ROOT'] . "/resources/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/project.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/northcode_api.php");

$where = " projects.public = '1' ";
if(isset($_GET['searchBy']))
if($_GET['searchBy'] != null && $_GET['searchBy'] != '')
{
	$s = $_GET['searchBy'];
	$where .= " AND (projects.name LIKE '%$s%') ";
}

if(isset($_GET['filterBy']))
if($_GET['filterBy'] != null && $_GET['filterBy'] != "" && $_GET['filterBy'] != "all")
{
	if($_GET['filterBy'] == "title") 
		$f = "name";
	else 
		$f = $_GET['filterBy'];
	$where .= " AND type = " . $f . " ";
}

$offset = ($_GET['pageIndex']) * $_GET['pageSize'];
$limit = $offset . "," . $_GET['pageSize'];

$order = "DESC";
$sort = "projects.date_modified";
if(isset($_GET['sortBy']))
if($_GET['sortBy'] != null && $_GET['sortBy'] != "")
{
	$sort = "projects." . $_GET['sortBy'];
}

if(isset($_GET['sortDirection']))
if($_GET['sortDirection'] != "")
	$order = $_GET['sortDirection'];


$Projects = Project::getProjects($where, "$sort $order", $limit);



class PayloadObj {
	public $total;
	public $items;
}

class PayloadItem {
	public $thumbnail;
	public $name;
	public $title;
	public $type;
	public $author;
	public $rating;
	public $downloads;
	public $date_created;
	public $date_modified;
	public $src;
}


$payload = new PayloadObj();
$payload->total = Project::getProjectsCount();

foreach($Projects as $p)
{
	$item = new PayloadItem();
	$item->src = $p->thumbnail_url;
	$item->thumbnail = '<a href="/project/' . $p->idname . '"><img width="200px" src="' . $p->thumbnail_url . '" alt=""></a>';
	$item->name = '<b><a href="/project/' . $p->idname . '">' . $p->name . '</a></b>';
	$item->title = '<b><a href="/project/' . $p->idname . '">' . $p->name . '</a></b><br><p>' . $p->shortDesc() . '</p>';
	$item->type = $p->typename;
	$item->author = $p->prettyAuthors();
	$item->rating = $p->rating;
	$item->downloads = $p->downloads;
	$item->date_created = $p->date_created;
	$item->date_modified = $p->date_modified;
	$payload->items[] = $item;
}

echo json_encode($payload);