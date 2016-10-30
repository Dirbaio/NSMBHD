<?php

$title = "IRC Chat";

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("IRC chat"), "irc"));
makeBreadcrumbs($crumbs);

$bad = array("~", "&", "@", "?", "!", ".", ",", "=", "+", "%", "*");
$handle = str_replace(" ", "", $loguser['name']);
$handle = str_replace($badchars, "_", $handle);
if(!$handle)
{
	$handle = "ABXDGuest";
	$guest = "<p>When you've connected to the IRC network, please use the command <kbd>/nick NICKNAME</kbd>.</p>";
}

$server = Settings::pluginGet("server");
$channel = Settings::pluginGet("channel");
$port = Settings::pluginGet("port");
if(isset($_GET['connect']))
{

	write("
	<div class=\"message\" style=\"width: 90%; margin: 2em auto; text-align: center;\">
		<h3 style=\"text-align: left;\">IRC chat</h3>
		<applet code=\"IRCApplet.class\" codebase=\"".resourceLink("plugins/ircpage/pjirc/")."\"
		archive=\"irc.jar,pixx.jar\" width=\"100%\" height=\"500\">
		<param name=\"CABINETS\" value=\"irc.cab,securedirc.cab,pixx.cab\">

		<param name=\"nick\" value=\"{0}\">
		<param name=\"alternatenick\" value=\"{0}_??\">
		<param name=\"fullname\" value=\"ABXD IRC User\">
		<param name=\"host\" value=\"{1}\">
		<param name=\"port\" value=\"{3}\">
		<param name=\"gui\" value=\"pixx\">
		<param name=\"authorizedcommandlist\" value=\"all-server-s\">

		<param name=\"quitmessage\" value=\"Leaving\">
		<param name=\"autorejoin\" value=\"true\">

		<param name=\"style:bitmapsmileys\" value=\"false\">
		<param name=\"style:backgroundimage\" value=\"false\">
		<param name=\"style:backgroundimage1\" value=\"none+Channel all 2 background.png.gif\">
		<param name=\"style:sourcecolorrule1\" value=\"all all 0=000000 1=ffffff 2=0000ff 3=00b000 4=ff4040 5=c00000 6=c000a0 7=ff8000 8=ffff00 9=70ff70 10=00a0a0 11=80ffff 12=a0a0ff 13=ff60d0 14=a0a0a0 15=d0d0d0\">

		<param name=\"pixx:timestamp\" value=\"true\">
		<param name=\"pixx:highlight\" value=\"true\">
		<param name=\"pixx:highlightnick\" value=\"true\">
		<param name=\"pixx:nickfield\" value=\"false\">
		<param name=\"pixx:styleselector\" value=\"true\">
		<param name=\"pixx:setfontonstyle\" value=\"true\">

		<param name=\"command1\" value=\"/join {2}\">

		</applet><br />
		<small style=\"float: right; opacity: 0.5;\">We recommend you get a stand-alone client such as <a href=\"http://hexchat.org\">HexChat</a> if you plan on frequently joining IRC</small>
		<br />
	</div>
", $handle, $server, $channel, $port);
}
else
{
	write("
	<div class=\"message margin\" style=\"width: 75%; margin: 2em auto; text-align: center;\">
	<h3 style=\"text-align: left;\">IRC chat</h3><br />
		<strong>Server:</strong> {1}:{4}<br />
		<strong>Channel:</strong> {2}<br />
		<strong>Nickname:</strong> {0}<br />
		<br />
		<a href=\"".actionLink("irc", "", "connect")."\"><button>".__("Use the board's IRC applet")."</button></a><br />
		<br />
		<small style=\"opacity: 0.5;\">(We recommend you get a stand-alone client such as <a href=\"http://hexchat.org\">HexChat</a> if you plan on frequently joining IRC)</small>
		<br /><br />
		{3}
	</div>
", $handle, $server, $channel, $guest, $port);
}

?>
