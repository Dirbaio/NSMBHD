<?php

function killhaxxor($log = 1)
{
	global $loguserid;

	if ($log)
	{
		$shitbugs = @file_get_contents('shitbugs.dat');
		$shitbugs = $shitbugs ? unserialize($shitbugs) : array();

		$entry = array('ip' => $_SERVER['REMOTE_ADDR'], 'date' => time(), 'banflags' => (1 << rand(0,10)));
		$shitbugs = array_merge(array($entry), $shitbugs);
		@file_put_contents('shitbugs.dat', serialize($shitbugs));

		setcookie('loguserid', $loguserid ? -$loguserid : -1337, time()+99999999);
	}

	echo
"<!doctype html>
<html>
	<head>
		<title>moron</title>
	</head>
	<body style=\"background: black; color: #f33; font-family: Verdana, sans-serif; text-align: center;\">
		Your request has been denied.
	</body>
</html>";

	die();
}

if ($_COOKIE['loguserid'] < 0) killhaxxor(0);

$check_query = '@(UNION.*?SELECT|password|--|/\*)@si';
$check_lenient = '@UNION.*?SELECT@si';

if (preg_match($check_query, urldecode($_SERVER['QUERY_STRING']))) killhaxxor();
foreach ($_POST as $val) if (preg_match($check_lenient, $val)) killhaxxor();
foreach ($_COOKIE as $val) if (preg_match($check_lenient, $val)) killhaxxor();

?>