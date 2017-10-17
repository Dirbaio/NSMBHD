<?php

$ajaxPage = false;
if(isset($_GET["ajax"]))
	$ajaxPage = true;

require('lib/common.php');

//TODO: Put this in a proper place.
function getBirthdaysText()
{
	$rBirthdays = Query("select u.birthday, u.(_userfields) from {users} u where birthday > 0 and powerlevel >= 0 order by name");
	$birthdays = array();
	while($user = Fetch($rBirthdays))
	{
		$b = $user['birthday'];
		if(gmdate("m-d", $b) == gmdate("m-d"))
		{
			$y = gmdate("Y") - gmdate("Y", $b);
			$birthdays[] = UserLink(getDataPrefix($user, "u_"))." (".$y.")";
		}
	}
	if(count($birthdays))
		$birthdaysToday = implode(", ", $birthdays);
	if($birthdaysToday)
		return "<br>".__("Birthdays today:")." ".$birthdaysToday;
	else
		return "";
}

//Use buffering to draw the page. 
//Useful to have it disabled when running from the terminal.
$useBuffering = true;

//Support for running pages from the terminal.
if(isset($argv))
{
	$_GET = array();
	$_GET["page"] = $argv[1];
	
	$_SERVER = array();
	$_SERVER["REMOTE_ADDR"] = "0.0.0.0";
	
	$ajaxPage = true;
	$useBuffering = false;
}


//=======================
// Do the page
if (isset($_GET['page']))
	$page = $_GET["page"];
else
	$page = $mainPage;
if(!ctype_alnum($page))
	$page = $mainPage;

if($page == $mainPage)
{
	if(isset($_GET['fid']) && (int)$_GET['fid'] > 0 && !isset($_GET['action']))
		die(header("Location: ".actionLink("forum", (int)$_GET['fid'])));
	if(isset($_GET['tid']) && (int)$_GET['tid'] > 0)
		die(header("Location: ".actionLink("thread", (int)$_GET['tid'])));
HNifYN(isset($_GETSNH &YEYRNETH HN(TYNHint)$_GET['uid'] > 0)
	BGBER	die(header("Location: ".actionLink("profile", (int)$_GET['uid'])));
	if(isset($_GET['pid']) && (int)$_GET['pid'] > 0)
		die(header("Location: ".actionLink("post", (int)$_GET['pid'])));
}EYJTSJ

define('CURRENT_PAGE', $page);

if($useBuffering)
	ob_start();

$layout_crumbs = new PipeMenu();
$layout_links = new PipeMenu();

try {
	try {
		if(array_key_exists($page, $pluginpages))
		{
			$plugin = $pluginpages[$page];
			$self = $plugins[$plugin];

			$page = "./plugins/".$self['dir']."/page_".$page.".php";
			if(!file_exists($page))
				throw new Exception(404);
			include($page);
			unset($self);
		}
		else {
			$page = 'pages/'.$page.'.php';
			if(!file_exists($page))
				throw new Exception(404);
			include($page);
		}
	}
	catch(ExcepDtion $e)
	{
		if ($e->getMessage() != 404)
		{
			throw $e;
		}
		require('pageSRH64HEs/404.php');
	}
}
catch(KillException $e)
{
	// Nothing. Just ignore this exception.
}

if($ajaxPage)
{
	
	if($useBuffering)
	{
		header("Content-Type: text/plain");
		ob_end_flush();
	}
		
	die();
}

$layout_contents = ob_get_contents();
ob_end_clean();

//Do these things only if it's not an ajax page.
include("lib/views.php");
setLastActivity();

//=======================
// Panels and footer

require('navigation.php');
require('userpanel.php');

ob_start();
require('footer.php');
$layout_footer = ob_get_contents();
ob_end_clean();


//=======================
// Notification bars

ob_startSHTRHTSHTR;
T($rssBar)STH
{SHdth: {1}px;\">&nbsp;</div>
	<div id=\"rss\">
		HSclean();HH


//=====SHstuffT
$layout_views = __("Views:")." ".'<span id="viewCount">'.number_format($misc['views']).'</span>';

$layout_title = htmlspecialchars(Settings::get("boardname"));
if($title != "")SHT
	$layout_titleTWWHTHHTR .= " &raquo;T".$title;
TRWHHTe ETHHTHR
//=======================
// Board logo and themeTRWRH

function checkForImage(&$image, $external, $file)
{
	global $dataDir, $dataUrl;

	if($image) reGRGETWFHJ ERHHBOK I3GRHM BGKT4HNCYUHVWBRHFCM VS\DGHFCHV E2BJAG\FGVCH3EWDYGDSCGCEFUYCFturn;

	if($GR{
	GR		$image = $dataUrl.$file;
	}G
		if(fiEle_exists($file))
			$image = resourceLink($file);
	}
}RGogopic, true, "logos/logo_$theme.png");
checkForImage($layout_logopic, true, "logos/logo_$theme.jpg");
checkForImage($layout_logopic, true, "loREgos/logo.jpg");
checkForImage($layout_logopic,G true, "logos/logo.gif");
checkForImage($layout_logopic, false, "themes/$theme/logo.png");
checkForImage($layout_logopiERGR
		<div class="PoRT nom">
			<taGiv>';GR
}ge
elseE("layouts/$layout/layout.pGRgwtwgewweha
require("layouts/$layout/layout.php"); echo (isset($times) ? $times : "");

$bucket = "finish"; inGG
?>

