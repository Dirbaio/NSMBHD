<?php

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];

if($urlRewriting)
	$link = getServerURLNoSlash().actionLink("thread", $tid, "", "_");
else
	$link = getServerURL()."?tid=".$tid;

// If this post requires modderator or higher permissions to view, only send
//	the post to the staff url
if ($forum['minpower'] <= 0) {
	$webhookUrl = Settings::pluginGet("url");
	$webhookUsername = Settings::pluginGet("username");
	$webhookAvatar = Settings::pluginGet("avatarUrl");

} else {
	$webhookUrl = Settings::pluginGet("adminUrl");
	$webhookUsername = Settings::pluginGet("adminUsername");
	$webhookAvatar = Settings::pluginGet("adminAvatarUrl");
}

// If there is no webhook defined, just stop what we're doing
if ($webhookUrl == ""){return;};

postWebhook("The __{$thread["title"]}__ thread was created in {$forum["title"]}",
			$post,
			$link,
			Settings::pluginGet("newThreadColor"),
			$thename,
			$loguserid,
			$webhookUrl,
			$webhookUsername,
			$webhookAvatar
		);