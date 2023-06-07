<?php
	$settings = array(
		"url" => array(
			"type" => "text",
			"default" => "localhost",
			"name" => "Discord Webhook URL",
		),
		"username" => array(
			"type" => "text",
			"default" => "NSMBHD",
			"name" => "The username that will show for the webhook message in chat.",
		),
		"avatarUrl" => array(
			"type" => "text",
			"default" => "",
			"name" => "The avatar that will show for the webhook message in chat.",
		),
        "newReplyColor" => array(
			"type" => "integer",
			"default" => "14483220",
			"name" => "The color of the embed used for when a new reply is made. (Must convert hex codes to int!)",
		),
        "newThreadColor" => array(
			"type" => "integer",
			"default" => "1376067",
			"name" => "The color of the embed used for when a new thread is made. (Must convert hex codes to int!)",
		),
	);
?>