<?php

header("Content-Type: application/json");

$data = array(
	"about" => "API version 1.1 for Cobalt Vault",
	"version" => "1.1"
	);

echo json_encode($data);