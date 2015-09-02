<?php
require_once("../lib/lib.php");
require_once("../lib/mail.php");
require_once("../resources/connect.php");

die("You can't register here");	


// VAR CHECK
$error = "";

$disallowed = array(
	"admin", 
	"owner",
	"jenjen1324",
	"northcode",
	"ass",
	"mother",
	"fuck",
	"fck"
	);

if(!isset($_POST['username']) || $_POST['username'] == "")
	$error .= "Missing Username<br />";
if(!isset($_POST['email']) || $_POST['email'] == "")
	$error .= "Missing E-Mail<br />";
if(!isset($_POST['password']) || $_POST['password'] == "")
	$error .= "Missing Password<br />";
if($_POST['password'] != $_POST['password_c'])	
	$error .= "Passwords do not match";

if (preg_match('/[^\w\d_ -]/si', $_POST['username']))
{
	$error .= "Only use the following characters: Aa-Zz, 0-9, -, _";
} 

if(contains($_POST['username'],$disallowed))
{
	$error .= "Your username contains disallowed words";
}

_error($error);

$_name = $_POST['username'];
$_email = $_POST['email'];
$_password = md5(MD5SALT.$_POST['password']);
$_registration_id = md5(rand());

// MYSQL

$query = "SELECT * FROM users WHERE username='$_name'";
$r = $mysql->query($query);
if($r->num_rows > 0)
	$error .= "Username already in use<br />";

$query = "SELECT * FROM users WHERE email='$_email'";
$r = $mysql->query($query);
if($r->num_rows > 0)
	$error .= "E-Mail already in use";

_error($error);

// INSERT

$query = "INSERT INTO users (username,email,password,registration_id,date_joined) VALUES ('$_name','$_email','$_password','$_registration_id',NOW())";
$mysql->query($query);

// MAIL

$subject = "Thank you for registering at Northcode";
$content = "Thank you that you registered at northcode.no<br/>
To complete your registration please click the following link: <a href='http://cobalt.northcode.no/scripts/confirm.php?id=$_registration_id'>http://cobalt.northcode.no/scripts/confirm.php?id=$_registration_id</a><br/>
Have a nice day<br/>
The northcode team";

sendMail($subject,$content,$_email,$_name,"..","Cobalt@Northcode");

$msg = "<b>Congratulations!</b> Your registration has succeeded! Check your mail for a confirmation link";
alert($msg,"success");