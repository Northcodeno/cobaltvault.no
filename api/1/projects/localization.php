<?php
header("Content-Type: application/json");

require_once("../../../resources/connect.php");
require_once("../../../lib/lib.php");

if(!check($_GET['id']))
	die("No project id");

$query = "SELECT projects.localization FROM projects WHERE id = '".$_GET['id']."'";
$data = $query->fetch_assoc();

echo $data['localization'];
echo "\n\r\n\r";