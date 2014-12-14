<?php

header("Content-Type: application/json");

require_once("../../../resources/connect.php");
require_once("../../../lib/lib.php");
require_once("../../../lib/northcode_api.php");

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

if(!isset($_GET['id']))
	die("No project id");

$id = $_GET['id'];

$query = "SELECT 
projects.id,
projects.name,
projects.desc,
projects.type,
projects.category,
projects.downloads,
projects.date_created,
projects.date_modified,
projects.author,
projects.version,
projects.rating,
projects.thumbnail_url,
projects.localization,
projects.filename,
projects_maptypes.name AS maptype
		FROM projects 
		LEFT JOIN projects_maptypes ON projects.type = projects_maptypes.id
		WHERE projects.public = '1' AND projects.id = '$id' LIMIT 1";

$res = $mysql->query($query);

$array = array();

$row = $res->fetch_assoc();

$uinfo = nc_api::get_user_info($row['author']);

$array["id"] = $row["id"];
$array["name"] = $row["name"];
$array["desc"] = $row["desc"];
$array["desc_stripped"] = str_replace("\r","",strip_tags($row['desc']));
$array["type"] = $row["type"];
$array["downloads"] = $row["downloads"];
$array["date_created"] = $row["date_created"];
$array["date_modified"] = $row["date_modified"];
$array['author'] = $uinfo['username'];
$array["author_id"] = $row["author"];
$array["version"] = $row["version"];
$array["rating"] = $row["rating"];
$array["thumbnail_url"] = $row["thumbnail_url"];
$array["has_localization"] = ($row["localization"] != "");
$array["file_type"] = "";

$res_2 = $mysql->query("SELECT comments.*
	FROM comments 
    WHERE comments.project = '$id'");

while($row_2 = $res_2->fetch_array())
{
	$uinfo_2 = nc_api::get_user_info($row_2['author']);
	$comment = array();
	$comment["title"] = $row_2["title"];
	$comment["message"] = $row_2["message"];
	$comment["message_stripped"] = str_replace("\r","",strip_tags($row_2['message']));
	$comment["date"] = $row_2["date"];
	$comment["author_id"] = $row_2["author"];
	$comment["author"] = $uinfo_2['username'];
	$array["comments"][] = $comment;
}

$final = array();
$final["about"] = "Project Information";
$final["data"] = $array;

$json = json_encode($final);
header("Content-Lenght: ".strlen($json));

echo $json;