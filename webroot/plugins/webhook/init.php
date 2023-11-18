<?php
function replaceQuotes($string) {
    // Define the pattern to match
    $pattern = '/\[quote="([^"]+)" id="([^"]+)"\](.*?)\[\/quote\]/s';

    while (preg_match($pattern, $string, $matches)) {
        // Fix line breaks in nested quotes
        $nestedContent = str_replace("\n", "\n\n", $matches[3]);
        
        // Append '>' in front of the text
        $nestedContent = '> ' . str_replace("\n", "\n> ", $nestedContent);
        
        $replacementString = "[Quoting {$matches[1]}](http://localhost/post/{$matches[2]}/)\n\n{$nestedContent}";
        $string = str_replace($matches[0], $replacementString, $string);
    }

    return $string;
}


function formatPost($post){
	$formatPost = $post;

	// Quote [quote="user" id="id"] | [/quote] -> [(Quoting user)](https://nsmbhd.net/post/id/)
	$formatPost = replaceQuotes($formatPost);

	// Bold [b] | [/b] -> ** | **
	$formatPost = str_replace("[b]", "**", $formatPost);
	$formatPost = str_replace("[/b]", "**", $formatPost);

	// Italics [i] | [/i] -> * | *
	$formatPost = str_replace("[i]", "*", $formatPost);
	$formatPost = str_replace("[/i]", "*", $formatPost);

	// Underline [u] | [/u] -> __ | __
	$formatPost = str_replace("[u]", "__", $formatPost);
	$formatPost = str_replace("[/u]", "__", $formatPost);

	// Strikethrough [s] | [/s] -> ~~ | ~~
	$formatPost = str_replace("[s]", "~~", $formatPost);
	$formatPost = str_replace("[/s]", "~~", $formatPost);

	// Code [code] | [/code] -> ` | `
	$formatPost = str_replace("[code]", "`", $formatPost);
	$formatPost = str_replace("[/code]", "`", $formatPost);

	// Spoilers [spoiler]| [/spoiler] -> || | ||
	$formatPost = str_replace("[spoiler]", "||", $formatPost);
	$formatPost = str_replace("[/spoiler]", "||", $formatPost);

	// Code Block [source=py] | [/source] -> ```py | ```
	//	TODO - Make it so the specified code source type is preserved
	$codePattern = "/\[source=(.*?)\]/";
	$formatPost = preg_replace($codePattern, "```$1\n", $formatPost);
	$formatPost = str_replace("[/source]", "\n```", $formatPost);

	return $formatPost;
}

function postWebhook($title, $description, $url, $color, $authorName, $authorID, $webhookUrl, $webhookUsername, $webhookAvatar){
    $timestamp = date("c", strtotime("now"));
    $json_data = json_encode([
        
        // Username
        "username" => $webhookUsername,
    
        // Avatar URL.
        // Uncomment to replace image set in webhook
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
                "description" => formatPost($description),
    
                // URL of title link
                "url" => "https://nsmbcentral.net",
    
                // Timestamp of embed must be formatted as ISO8601
                "timestamp" => $timestamp,
    
                // Embed left border color in HEX
                "color" => $color,
    
                // Author
                "author" => [
                    "name" => $authorName,
                    "avatar_url" => "http://localhost:8000/data/avatars/$authorID/"
                ]
            ]
        ]
    
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    curl_close($ch);
}