<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

die("This is obsolete!");

require_once("../resources/connect.php");
require_once("../lib/lib.php");

$id = $_GET['id'];
$redirect = "http://cobalt.northcode.no/";

$query = "SELECT * FROM users WHERE registration_id = '$id' LIMIT 1";
$r = $mysql->query($query);
if($r->num_rows > 0)
{
	$row = $r->fetch_assoc();
	$username = $row['username'];
	if($row['confirmed'] == 1)
	{
		alert("The account $username has already been confirmed","info",$redirect);
	}
	else
	{
		$mysql->query("UPDATE users SET confirmed=1 WHERE registration_id='$id'");
		$msg = "Account $username was confirmed";
		alert($msg,"success",$redirect);
	}
}

_error("Something went wrong", $redirect);