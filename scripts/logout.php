<?php

include_once("../lib/northcode_api.php");
include_once("../lib/lib.php");

session_start();
$session = nc_session::load_from_session();
//$session->destroy_session();
$session->logout();

redirect();