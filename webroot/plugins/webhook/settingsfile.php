<?php
	$settings = array(
		// The needed parameters 
		"url" => array(
			"type" => "text",
			"default" => "localhost",
			"name" => "The Discord Webhook URL you want to send posts to.",
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

		// Admin versions of the first parameters
		"adminUrl" => array(
			"type" => "text",
			"default" => "",
			"name" => "An optional second Discord Webhook URL for posts that require permissions higher than being a regular member.",
		),
		"adminUsername" => array(
			"type" => "text",
			"default" => "",
			"name" => "The username that will show for the admin webhook message in chat.",
		),
		"adminAvatarUrl" => array(
			"type" => "text",
			"default" => "",
			"name" => "The avatar that will show for the admin webhook message in chat.",
		),

		// Embed colors
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