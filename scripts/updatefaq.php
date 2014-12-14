<?php 

require_once("../lib/lib.php");
require_once("../resources/connect.php");

if (!($_SESSION['rank'] > 8))
{
	die();
}

$content = $_POST['content'];

$mysql->query("UPDATE faq SET content = '$content' WHERE cat = 2");

redirect();

 ?>