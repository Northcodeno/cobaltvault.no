<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


die("Already migrated to latest version!"); // Done for now...

require_once("resources/connect.php");
require_once("lib/northcode_api.php");
require_once("lib/project.php");

function changeFileEnding($filename, $ending)
{
	$tmp = explode('.', $filename);
	$tmp[sizeof($tmp)-1] = $ending;
	return implode('.', $tmp);
}

function e()
{
	global $mysql;
	if($mysql->error)
		die($mysql->error);
}

echo "Migration script<br>";


echo "Fetching Projects<br>";
// Fetch Projects
$projects = array();

$stmt = $mysql->prepare("SELECT id,name,author,filename,localization,filename_zip FROM projects WHERE format_version = 0"); e();
$stmt->execute();
$stmt->bind_result($_id,$_name,$_author,$_filename,$_localization,$_filename_zip);

while($stmt->fetch())
{
	$projects[] = array("id" => $_id, "name" => $_name, "author" => $_author, "filename" => $_filename, "localization" => $_localization, "filename_zip" => $_filename_zip);
}

$stmt->close();

echo "<em>" . var_dump($projects) . "</em><br>";

echo "Looping Projects<br>";

foreach($projects as $P)
{

	echo "Migrating: " . $P['id'] . " | " . $P['name'] . "<br>";

	// Add Author to authors table
	$stmt = $mysql->prepare("INSERT INTO projects_authors (`pid`,`uid`) VALUES (?,?)"); e();
	$stmt->bind_param('ii',$P['id'],$P['author']);
	$stmt->execute();
	$stmt->close();


	// Add map to project
	$stmt = $mysql->prepare("INSERT INTO projects_maps (`pid`,`name`) VALUES (?,?)"); e();
	$stmt->bind_param('is',$P['id'],$P['name']);
	$stmt->execute();
	$iid = $stmt->insert_id;
	$stmt->close();

	// Add files to project
	$tmp = explode(".",$P['filename']);
	$ext = end($tmp);
	if($ext == "map")
	{
		$stmt = $mysql->prepare("INSERT INTO projects_files (`mid`,`filename`,`type`) VALUES (?,?,1)"); e();
		$stmt->bind_param('is',$iid,$P['filename']);
		$stmt->execute();
		$iid2 = $stmt->insert_id;
		$stmt->close();

		rename("files/" . $P['filename'], "files/project_files/" . $iid2);

		if(isset($P['localization']) && $P['localization'] != "")
		{
			$stmt = $mysql->prepare("INSERT INTO projects_files (`mid`,`filename`,`type`) VALUES (?,?,2)"); e();
			$ending = changeFileEnding($P['filename'],"localization");
			$stmt->bind_param('is',$iid,$ending);
			$stmt->execute();
			$iid3 = $stmt->insert_id;
			$stmt->close();

			file_put_contents("files/project_files/" . $iid3, $P['localization']);
		}
	}
	elseif($ext == "zip" || $ext == "rar")
	{
		$stmt = $mysql->prepare("INSERT INTO projects_files (`mid`,`filename`,`type`) VALUES (?,?,4)"); e();
		$stmt->bind_param('is',$iid,$P['filename']);
		$stmt->execute();
		$iid2 = $stmt->insert_id;
		$stmt->close();

		rename("files/" . $P['filename'], "files/project_files/" . $iid2);
	}

	$stmt = $mysql->prepare("UPDATE projects SET format_version = 1 WHERE id = ?"); e();
	$stmt->bind_param('i',$P['id']);
	$stmt->execute();
	$stmt->close();
}

?>