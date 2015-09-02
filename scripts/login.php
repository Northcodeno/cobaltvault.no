<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once("../lib/lib.php");
require_once("../lib/northcode_api.php");

$uname = $_POST['username'];
$passw = $_POST['password'];

$session;
try
{
	$session = new nc_session($uname,$passw);
}
catch (Exception $ex)
{
	_error($ex->getMessage());
}

$session->store_in_session();

alert("Login Successful!","success");

var_dump($_SESSION);