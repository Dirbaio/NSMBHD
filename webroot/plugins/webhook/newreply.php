<?php

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];

if($urlRewriting)
	$link = getServerURLNoSlash().actionLink("post", $pid, "", "_");
else
	$link = getServerURL()."?pid=".$pid;

postWebhook("New reply in the {$thread["title"]} thread. ({$forum["title"]})",
			"{$post}",
			$link,
			Settings::pluginGet("newReplyColor"),
			$thename
);
