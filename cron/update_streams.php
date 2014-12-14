<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


/// -- Checks twitch for streams to corresponding game --


include("../resources/connect.php");

// Clear Table
$stmt = $mysql->prepare("TRUNCATE streams");
$stmt->execute();
$stmt->close();

// Make API request
$game = "Cobalt";

$res = file_get_contents("https://api.twitch.tv/kraken/search/streams?q=$game");

$obj = json_decode($res);

// Iterate streams and determine the Game
if($obj)
{
	foreach($obj->streams as $s)
	{
		if($s->game == $game)
		{
			// Add the stream to table
			add_stream($s);
		}
	}
}

/// Adds stream to table
/// $obj -> Stream object from twitch API
function add_stream($obj)
{
	$name = $obj->channel->name;
	$dname = $obj->channel->display_name;

	print ($obj->channel->name . "<br>" . $obj->channel->display_name . "<br><br>");


	global $mysql;

	$stmt = $mysql->prepare("INSERT INTO streams (`username`,`display_name`) VALUES (?,?)");
	$stmt->bind_param('ss', $name, $dname);
	$stmt->execute();
	$stmt->close();
}

echo "Success";