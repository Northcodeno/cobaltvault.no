<?php

require_once("../resources/connect.php");

$maps = array();
$files = array();


$stmt = $mysql->prepare("SELECT id, pid FROM projects_maps");
$stmt->execute();
$stmt->bind_result($id,$pid);

while($stmt->fetch())
{
	$maps[] = array($id, $pid);
}

$stmt->close();

foreach($maps as $m)
{
	$stmt = $mysql->prepare("UPDATE projects_files SET pid = ? WHERE mid = ?");
	$stmt->bind_param('ii',$m[1],$m[0]);
	$stmt->execute();
	$stmt->close();
}