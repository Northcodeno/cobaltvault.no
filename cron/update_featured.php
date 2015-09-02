<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


/// -- Chooses featured maps --

$featured = array();

include("../resources/connect.php");

// Clear Table
$stmt = $mysql->prepare("TRUNCATE projects_featured");
$stmt->execute();
$stmt->close();

// 3 Random maps
$stmt = $mysql->prepare("SELECT id FROM projects WHERE public = 1 AND thumbnail_url != '' ORDER BY RAND() LIMIT 0,3");
$stmt->execute();
$stmt->bind_result($id);
while($stmt->fetch())
	$featured[] = $id;
$stmt->close();

// Top Download
$stmt = $mysql->prepare("SELECT id FROM projects WHERE public = 1 AND thumbnail_url != '' ORDER BY downloads DESC LIMIT 1");
$stmt->execute();
$stmt->bind_result($id);
$stmt->fetch();
$featured[] = $id;
$stmt->close();

// Latest
$stmt = $mysql->prepare("SELECT id FROM projects WHERE public = 1 AND thumbnail_url != '' ORDER BY date_created DESC LIMIT 1");
$stmt->execute();
$stmt->bind_result($id);
$stmt->fetch();
$featured[] = $id;
$stmt->close();

// Insert into db

foreach($featured as $pid)
{
	$stmt = $mysql->prepare("INSERT INTO projects_featured (`pid`) VALUES (?)");
	$stmt->bind_param('i',$pid);
	$stmt->execute();
	$stmt->close();
}