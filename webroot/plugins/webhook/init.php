<?php
function replaceQuotes($input) {
    // Helper function to add ">" to each new line and add an extra newline at the end
    function formatQuoteContent($content) {
        $lines = explode("\n", trim($content)); // Split the content into lines
        $formattedLines = array_map(function($line) {
            return "> " . trim($line); // Add "> " to each line and trim it
        }, $lines);
        return implode("\n", $formattedLines) . "\n"; // Rejoin the lines and add an extra newline at the end
    }

    // Pattern 1: Matches [quote="Ndymario"]Hi[/quote]
    $pattern1 = '/\[quote="(.*?)"\](.*?)\[\/quote\]/is';
    $replacement1 = function($matches) {
        return "*Posted by " . $matches[1] . "*\n" . formatQuoteContent($matches[2]);
    };
    
    // Pattern 2: Matches [quote="Ndymario" id=44]Hi[/quote]
    $pattern2 = '/\[quote="(.*?)" id="?(\d+)"?\](.*?)\[\/quote\]/is';
    if ($urlRewriting) {
        $replacement2 = function($matches) {
            return '[*Posted by ' . $matches[1] . '*](' . getServerURLNoSlash() . actionLink("post", $matches[2], "", "_") . ')' . "\n" . formatQuoteContent($matches[3]);
        };
    } else {
        $replacement2 = function($matches) {
            return '[*Posted by ' . $matches[1] . '*](' . getServerURL() . "?pid=" . $matches[2] . ')' . "\n" . formatQuoteContent($matches[3]);
        };
    }
    
    // Pattern 3: Matches [quote]Hi[/quote]
    $pattern3 = '/\[quote\](.*?)\[\/quote\]/is';
    $replacement3 = function($matches) {
        return formatQuoteContent($matches[1]);
    };

    // Pattern 4: Matches [quote Ndymario id=44]Hi[/quote] (no equals sign for the name)
    $pattern4 = '/\[quote\s+([^\]=\s]+(?:\s+id="?(\d+)"?)?)\](.*?)\[\/quote\]/is';
    $replacement4 = function($matches) {
        return "*Posted by " . $matches[1] . "*\n" . formatQuoteContent($matches[3]);
    };

    $output = preg_replace_callback($pattern2, $replacement2, $input); // First, handle quotes with IDs and author
    $output = preg_replace_callback($pattern1, $replacement1, $output); // Then, handle quotes with author only
    $output = preg_replace_callback($pattern4, $replacement4, $output); // Then, handle quotes without "="
    $output = preg_replace_callback($pattern3, $replacement3, $output); // Finally, handle anonymous quotes

    return $output;
}


function replaceURLs($input) {
    // Pattern 1: Matches [url=<some url>]Hello world[/url]
    $pattern1 = '/\[url=(.*?)\](.*?)\[\/url\]/i';
    // Replacement: Convert to [Hello world](<some url>)
    $replacement1 = '[$2]($1)';
    
    // Pattern 2: Matches [url]<some url>[/url]
    $pattern2 = '/\[url\](.*?)\[\/url\]/i';
    // Replacement: Convert to <some url>
    $replacement2 = '$1';
    
    // First replace pattern 1
    $output = preg_replace($pattern1, $replacement1, $input);
    
    // Then replace pattern 2
    $output = preg_replace($pattern2, $replacement2, $output);
    
    return $output;
}



function formatPost($post){
	$formatPost = $post;

    // Code Block [source=py] | [/source] -> ```py | ```
	$codePattern = "/\[source=(.*?)\]/";
	$formatPost = preg_replace($codePattern, "```$1\n", $formatPost);
	$formatPost = str_replace("[/source]", "\n```", $formatPost);

	// Quote [quote="user" id="id"] | [/quote] -> [(Quoting user)](https://nsmbhd.net/post/id/)
	$formatPost = replaceQuotes($formatPost);

    // URL [url=<some url>] | [/url] -> [<the original message text>](<some url>)
    // [url]<some url>[/url] = <some url>
    $formatPost = replaceURLs($formatPost);

	// Bold [b] | [/b] -> ** | **
	$formatPost = str_replace("[b]", "**", $formatPost);
	$formatPost = str_replace("[/b]", "**", $formatPost);
    $formatPost = str_replace("[B]", "**", $formatPost);
	$formatPost = str_replace("[/B]", "**", $formatPost);

	// Italics [i] | [/i] -> * | *
	$formatPost = str_replace("[i]", "*", $formatPost);
	$formatPost = str_replace("[/i]", "*", $formatPost);
    $formatPost = str_replace("[I]", "*", $formatPost);
	$formatPost = str_replace("[/I]", "*", $formatPost);

	// Underline [u] | [/u] -> __ | __
	$formatPost = str_replace("[u]", "__", $formatPost);
	$formatPost = str_replace("[/u]", "__", $formatPost);
    $formatPost = str_replace("[U]", "__", $formatPost);
	$formatPost = str_replace("[/U]", "__", $formatPost);

	// Strikethrough [s] | [/s] -> ~~ | ~~
	$formatPost = str_replace("[s]", "~~", $formatPost);
	$formatPost = str_replace("[/s]", "~~", $formatPost);
    $formatPost = str_replace("[S]", "~~", $formatPost);
	$formatPost = str_replace("[/S]", "~~", $formatPost);

	// Code [code] | [/code] -> ` | `
	$formatPost = str_replace("[code]", "`", $formatPost);
	$formatPost = str_replace("[/code]", "`", $formatPost);

	// Spoilers [spoiler]| [/spoiler] -> || | ||
	$formatPost = str_replace("[spoiler]", "||", $formatPost);
	$formatPost = str_replace("[/spoiler]", "||", $formatPost);

	return $formatPost;
}

function postWebhook($title, $description, $url, $color, $authorName, $authorID, $webhookUrl, $webhookUsername, $webhookAvatar){
    $timestamp = date("c", strtotime("now"));
    $serverURL = getServerURL();
    $json_data = json_encode([
        
        // Username
        "username" => $webhookUsername,
    
        // Avatar URL.
        // Uncomment to replace image set in webhook
        "avatar_url" => $webhookAvatar,
    
        // File upload
        // "file" => "",
    
        // Embeds Array
        "embeds" => [
            [
                // Embed Title
                "title" => $title,

                // Embed Description
                "description" => formatPost($description),

                // URL of title link
                "url" => $url,

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

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    curl_close($ch);
    // if ($response != "") {
    //     kill(__("Something went wrong with the webhook. $response   $url"));
    // }
}