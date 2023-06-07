<?php

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];

if($urlRewriting)
	$link = getServerURLNoSlash().actionLink("thread", $tid, "", "_");
else
	$link = getServerURL()."?tid=".$tid;

postWebhook("The {$thread["title"]}** thread was created in {$forum["title"]}",
			"$post",
			$link,
			Settings::pluginGet("newThreadColor"),
			$thename
);