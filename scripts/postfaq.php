<?php 

require_once("../lib/lib.php");
require_once("../resources/connect.php");

$session = nc_session::load_from_session();
$userinfo = $session->get_user_info();

if (!($userinfo['title'] == 'Owner'))
{
	die();
}

$id = $_GET['id'];
$title = $_POST['title'];
$content = $_POST['content'];

$mysql->query("INSERT INTO faq (cat,title,content) VALUES ('$id','$title','$content')");

redirect();

 ?>