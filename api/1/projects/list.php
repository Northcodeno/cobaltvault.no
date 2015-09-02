<?php

header("Content-Type: application/json");

require_once("../../../resources/connect.php");
require_once("../../../lib/lib.php");
require_once("../../../lib/northcode_api.php");


$add = "";
if(isset($_GET['order']))
	$add .= "ORDER BY " . $mysql->escape_string($_GET['order']);
if(isset($_GET['sort']))
	$add .= " " . $mysql->escape_string($_GET['sort']);
if(isset($_GET['limit']))
	$add .= " LIMIT " . $mysql->escape_string($_GET['limit']);

$query = "SELECT 
projects.id,
projects.name,
projects.type,
projects.author,
projects.version,
projects_maptypes.name AS maptype
		FROM projects 
		LEFT JOIN projects_maptypes ON projects.type = projects_maptypes.id
		WHERE projects.public = '1' $add";

$res = $mysql->query($query);

echo $mysql->error;

$array = array();

while($row = $res->fetch_array())
{
	$uinfo = nc_api::get_user_info($row['author']);
	$pr = array();
	$pr['id'] = $row['id'];
	$pr['name'] = $row['name'];
	$pr['maptype'] = $row['maptype'];
	$pr['maptype_id'] = $row['type'];
	$pr['author'] = $uinfo['username'];
	$pr['author_id'] = $row['author'];
	$array[] = $pr;
}

$final = array(
	"about" => "List all projects",
	"projects" => $array
	);

$json = json_encode($final);
header("Content-Lenght: ".strlen($json));

echo $json;