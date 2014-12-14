<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include("../lib/lib.php");
include("../resources/connect.php");
require_once("../lib/northcode_api.php");

$session = nc_session::load_from_session();
$userinfo = $session->get_user_info();

if (!($userinfo['rank'] > 7))
{
	die();
}

$title = $_POST['title'];
$text = $_POST['text'];

$query = "INSERT INTO news (title,`text`) VALUES ('$title','$text')";
$mysql->query($query);

redirect();

?>