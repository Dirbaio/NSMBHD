<?php

$bad = array("~", "&", "@", "?", "!", ".", ",", "=", "+", "%", "*");
$handle = str_replace(" ", "", $loguser['name']);
$handle = str_replace($badchars, "_", $handle);
$prompt = "";
if(!$handle)
{
	$handle = "ABXDGuest.";
	$prompt = "&prompt=1";
}

$server = Settings::pluginGet("server");
$channels = Settings::pluginGet("channels");

$handle = urlencode($handle);
$channels = urlencode($channels);

redirect("$server/?nick=$handle&channels=$channels$prompt");


