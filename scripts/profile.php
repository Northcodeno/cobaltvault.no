<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once("../lib/lib.php");
require_once("../resources/connect.php");
require_once("../lib/HtmlPurifier/HTMLPurifier.auto.php");
require_once("../lib/northcode_api.php");
define("DIR","../files/pimages/");

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
try
{
	$session = nc_session::load_from_session();
	$userinfo = $session->get_user_info();
}
catch (Exception $ex)
{
	_error("You are not logged in!");
}

function upload_file($username)
{
	$allowedTypes = array("png","jpg");
	$tmp = explode(".",$_FILES['file']['name']);
	$ext = end($tmp);
	if(!in_array($ext,$allowedTypes))
		_error("Invalid file type<br/>Only .jpg and .png are allowed!");
	if ($_FILES["file"]["error"] > 0)
    {
		_error($_FILES["file"]["error"]);
    }

    if (file_exists(DIR . $_FILES["file"]["name"]))
	{
		_error($_FILES["file"]["name"] . " already exists. ");
	}
	move_uploaded_file($_FILES["file"]["tmp_name"],DIR . $username . "." . $ext);
}


if(isset($userinfo))
{
	if($userinfo['uid'] != $_GET['id'])
		_error("You are not authorized to edit this profile!");

	$id = $userinfo['uid'];

	if(isset($_POST['bio']))
	{
		$bio = $purifier->purify($_POST['bio']);
		echo $bio . "<br>";
		$resp = $session->edit_user(array("change" => array("info" => $bio)));
		alert("Success");
	}
	elseif(isset($_FILES["file"]))
	{
		$udata = $session->get_user_info();
		$username = $udata['username'];

		if(file_exists(DIR . $username . ".png"))
		{		
			try
			{
				unlink(DIR . $username . ".png");
			}
			catch (Exception $e) {}
			
		}

		if(file_exists(DIR . $username . ".jpg"))
		{		
			try
			{
				unlink(DIR . $username . ".jpg");
			}
			catch (Exception $e) {}
			
		}

		upload_file($username);

		alert("Success");
	}

}
else
{
	_error("No UID given");
}

?>