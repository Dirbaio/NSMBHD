<?php

$c1 = ircColor(Settings::pluginGet("color1"));
$c2 = ircColor(Settings::pluginGet("color2"));

$extra = "";

if($urlRewriting)
	$link = getServerURLNoSlash().actionLink("profile", $user["id"], "", "_");
else
	$link = getServerURL()."?uid=".$user["id"];


$pass = array();
$ip = array();

if(Settings::pluginGet("reportPassMatches"))
{
	$rLogUser = Query("select id, name, pss, password from {users} where 1");
	$matchCount = 0;

	while($testuser = Fetch($rLogUser))
	{
		if($testuser["id"] == $user["id"])
			continue;

		$sha = doHash($user["rawpass"].$salt.$testuser['pss']);
		if($testuser['password'] == $sha)
			$pass[] = $testuser['name'];
	}

	if($matchCount)
		$extra .= "-- ".Plural($matchCount, "password match")." ";
}


if(Settings::pluginGet("reportIPMatches"))
{
	$matches = query("select name from {users} where id != {0} and lastip={1}", $user["id"], $_SERVER["REMOTE_ADDR"]);
	while($u = fetch($matches))
		$ip[] = $u['name'];
}

	ircReport("\003".$c2."New user: \003$c1"
		.ircUserColor($user["name"], $user['sex'], $user['powerlevel'])
		."\003$c2 -- "
		.$link
		);

	ircReport("\003".$c2."New user: \003$c1"
		.ircUserColor($user["name"], $user['sex'], $user['powerlevel'])
		."\003$c2 -- "
		.$link
		, -1);

foreach($pass as $e)
	ircReport("\003".$c2."PASSWORD MATCH: \003$c1"
		.ircUserColor($user["name"], $user['sex'], $user['powerlevel'])
		."\003$c2 -- \003$c1 ".$e
		, -1);
foreach($ip as $e)
	ircReport("\003".$c2."IP MATCH: \003$c1"
		.ircUserColor($user["name"], $user['sex'], $user['powerlevel'])
		."\003$c2 -- \003$c1 ".$e
		, -1);
