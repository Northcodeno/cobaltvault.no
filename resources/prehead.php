<?php
/*
header("Location: /error/database.html");
die();
*/
/*
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/resources/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/lib.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/northcode_api.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/project.php");
define("ADFLY","http://adf.ly/7004056/dev.cobaltvault.no");


$NC_LOGGED_IN;
$NC_SESSION;
$NC_USERINFO;

if(isset($_SESSION['nc_ssid']))
{
	$NC_SESSION = new nc_session($_SESSION['nc_ssid'],$_SESSION['nc_code'],true);
	if(!$NC_SESSION->is_logged_in())
	{
		$NC_SESSION->logout();
		$NC_LOGGED_IN = false;
	}
	else
	{
		try
		{
			$NC_USERINFO = $NC_SESSION->get_user_info();
			$NC_LOGGED_IN = true;
		} catch (Exception $e)
		{
			die($e);
		}
	}
}
else
{
	$NC_LOGGED_IN = false;
}
?>
