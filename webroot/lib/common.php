<?php
// AcmlmBoard XD support - Main hub

// error_reporting is set in php.ini. Don't override it here.

require("../vendor/autoload.php");

$boardroot = preg_replace('{/[^/]*$}', '/', $_SERVER['SCRIPT_NAME']);

function usectime()
{
	$t = gettimeofday();
	return $t['sec'] + ($t['usec'] / 1000000);
}
$timeStart = usectime();

include("config.php");

include("version.php");
include("dirs.php");
include("settingsfile.php");
include("debug.php");

include("mysql.php");
if(!sqlConnect())
	die("Can't connect to the board database. Check the installation settings");
if(!fetch(query("SHOW TABLES LIKE '{misc}'")))
	die(header("Location: install.php"));

include("mysqlfunctions.php");
include("settingssystem.php");
Settings::load();
Settings::checkPlugin("main");
include("feedback.php");
include("language.php");
include("write.php");
include("snippets.php");
include("links.php");
include("turnstile.php");


class KillException extends Exception { }
date_default_timezone_set("GMT");

$title = "";

//WARNING: These things need to be kept in a certain order of execution.

include("browsers.php");
include("pluginsystem.php");
loadFieldLists();
include("loguser.php");
include("permissions.php");
canonicalize();
include("ranksets.php");
include("post.php");
include("log.php");
include("onlineusers.php");

include("htmlfilter.php");
include("smilies.php");

$theme = $loguser['theme'];
include('lib/layout.php');

//Classes
include("./class/PipeMenuBuilder.php");

include("lists.php");

$mainPage = "board";
$bucket = "init"; include('lib/pluginloader.php');
