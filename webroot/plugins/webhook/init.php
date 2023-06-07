<?php
function postWebhook($title, $description, $url, $color, $authorName, $webhookUrl, $webhookUsername, $webhookAvatar){
	$timestamp = date("c", strtotime("now"));
	$json_data = json_encode([
		
		// Username
		"username" => $webhookUsername,
	
		// Avatar URL.
		// Uncoment to replace image set in webhook
		"avatar_url" => $webhookAvatar,
	
		// Text-to-speech
		"tts" => false,
	
		// File upload
		// "file" => "",
	
		// Embeds Array
		"embeds" => [
			[
				// Embed Title
				"title" => $title,
	
				// Embed Type
				"type" => "rich",
	
				// Embed Description
				"description" => $description,
	
				// URL of title link
				"url" => "https://nsmbhd.net",
	
				// Timestamp of embed must be formatted as ISO8601
				"timestamp" => $timestamp,
	
				// Embed left border color in HEX
				"color" => $color,
	
				// Author
				"author" => [
					"name" => $authorName
				]
			]
		]
	
	], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

	$ch = curl_init( $webhookUrl );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );
	// A debug die.
	// die("Some variables: {$title}, {$description}, {$url}, {$timestamp}, {$color}, {$authorName} \n {$response}");
	curl_close( $ch );
}