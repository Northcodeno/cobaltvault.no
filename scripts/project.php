<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once("../resources/connect.php");
require_once("../lib/lib.php");
require_once("../lib/phpQuery-onefile.php");
require_once("../lib/HtmlPurifier/HTMLPurifier.auto.php");
require_once("../lib/northcode_api.php");
require_once("../lib/project.php");
define("DIR","../files/");


if(!isset($_SESSION['nc_ssid']))
	_error("You need to be logged in!");
$session = nc_session::load_from_session();
$userinfo = $session->get_user_info();

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

if(isset($_GET['a']))
	if($_GET['a'] == "create")
	{
		if(!isset($_POST['name']) || $_POST['name'] == "")
			_error("No projectname provided!");
		if(!isset($_POST['idname']) || $_POST['idname'] == "")
			_error("No idname provided!");
		if(preg_match('/[^a-z]+/', $_POST['idname'], $m))
			_error("Only lowercase letters are allowed for idname!");
		if(!isset($_POST['desc']) || $_POST['desc'] == "")
			_error("No description provided!");
		try
		{
			$P = Project::create($_POST, $userinfo['uid']);
		}
		catch (Exception $e)
		{
			_error($e);
		}
		
		redirect(null,"/project/" . $P);
		header("Location: /");
	}

if(!isset($_GET['id']) || !isset($_GET['a']))
	die("Missing parameters");

try
{
	$Project = new Project($_GET['id']);
}
catch (Exception $ex)
{
	_error($ex->getMessage());
}

if ($_GET['a'] != "rate" && $_GET['a'] != "comment" && $_GET['a'] != "delcomment")
if (!($Project->isAuthor($userinfo['uid'])))
{
	_error("You are not permittet to edit this project");
}


try
{

	switch($_GET['a'])
	{
		case "delete":
			$Project->delete();
			alert("Project deleted!","info","/");
			break;

		case "edit":
			$data = array();
			if(isset($_POST['name']))
				$data['name'] = stripslashes($purifier->purify($_POST['name']));
			if(isset($_POST['desc']))
				$data['desc'] = stripslashes($_POST['desc']);
			if(isset($_POST['type']))
				$data['type'] = $_POST['type'];
			$Project->edit($data);
			redirect(null,"/project/" . $Project->idname);
			break;

		case "public":
			$Project->edit(array("public" => 1));
			alert("Project was made public!");
			break;

		case "private":
			$Project->edit(array("public" => 0));
			alert("Project was hidden!");
			break;

		case "addfile":
			/// Adds a File to a map; File parameter is named "file"
			try
			{
				$result = $Project->addFile($_FILES['file']);
				exit($result);
			}
			catch (Exception $ex)
			{
				exit("Error: $ex");
			}
			break;

		case "updatefile":
			if(!isset($_GET['map']) || !isset($_GET['file']))
				throw new Exception("Missing data");
			$Map = getMap($Project,$_GET['map']);
			$m->updateFile($_GET['file'],$_FILES['file']);
			alert("File updated!");
			break;

		case "updateloc":
			if(!isset($_POST['loc']) || $_POST['loc'] == "")
				throw new Exception("No localization provided!");
			$Map = getMap($Project,$_GET['map']);
			$Map->updateLocalizationFromString($_POST['loc']);
			alert("Localization added!");
			break;

		case "delfile":
			if(!isset($_GET['file']))
				throw new Exception("Missing data");
			$Project->delFile($_GET['file']);
			alert("File deleted!");
			break;

		case "addloc":
			if(!isset($_POST['loc']) || $_POST['loc'] == "")
				throw new Exception("No localization provided!");
			$Map = getMap($Project,$_GET['map']);
			$Map->addLocalizationFromString($_POST['loc']);
			alert("Localization added!");
			break;

		case "rate":
			if(!isset($_GET['v']))
				throw new Exception("Missing data");
			$Project->rate($userinfo['uid'], $_GET['v']);
			alert("Map rated!");
			break;

		case "comment":
			if(!isset($_POST['text']))
				throw new Exception("Missing data");
			if(isset($_GET['reply']))
				$Project->comment($userinfo['uid'], $_POST['text'], $_GET['reply']);
			else
				$Project->comment($userinfo['uid'], $_POST['text']);
			alert("Comment posted!");
			break;

		case "delcomment":
			if(!isset($_GET['cid']))
				throw new Exception("Missing data");
			$Project->delComment($_GET['cid'], $userinfo['uid']);
			alert("Comment deleted");
			break;
	}

}
catch(Exception $ex)
{
	_error($ex->getMessage());
}

header("Location: /");


function getMap($P,$mapid)
{
	$m;
	foreach($P->maps as $map)
	{
		if($map->id == $mapid)
		{
			$m = $map;
			break;
		}
	}
	if(!isset($m))
		throw new Exception("Map not found in Project");

	return $m;
}