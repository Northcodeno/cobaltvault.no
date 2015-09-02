<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

if(!isset($_GET['type']))
	header("Location: index.php?m=Type missing!");
if(!isset($_GET['id']))
	header("Location: index.php?m=Id missing!");

require_once("resources/connect.php");
require_once("lib/project.php");
require_once("lib/northcode_api.php");

try
{
	if($_GET['type'] == "project")
	{
		$P = new Project($_GET['id']);
		// if(!$P->public)
		// 	throw new Exception("Project is not public!");
		$P->download();
		return;
	}
	elseif($_GET['type'] == "map")
	{
		$P = Project::getProjectFromMap($_GET['id']);
		// if(!$P->public)
		// 	throw new Exception("Project is not public!");
		$Map = $P->findMapById($_GET['id']);
		$Map->download();
		return;
	}
	elseif($_GET['type'] == "file")
	{
		$P = Project::getProjectFromFile($_GET['id']);
		// if(!$P->public)
		// 	throw new Exception("Project is not public!");
		$File = $P->findFileById($_GET['id']);
		$File->download();
		return;
	}
}
catch(Exception $e)
{
	header("Location: index.php?m=" . $e->getMessage());
}