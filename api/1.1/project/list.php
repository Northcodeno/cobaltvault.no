<?php

header("Content-Type: application/json");

require_once($_SERVER['DOCUMENT_ROOT'] . "/resources/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/project.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/northcode_api.php");

$projects = Project::getProjects();


$data = array(
	"about" => "A list of Projects",
	"projects" => $projects
	);

echo json_encode($data);