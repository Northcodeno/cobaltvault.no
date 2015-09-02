<?php

header("Content-Type: application/json");

$data = array(
	"about" => "API version 1 for Cobalt Vault"
	);

echo json_encode($data);