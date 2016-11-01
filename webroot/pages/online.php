<?php
//  AcmlmBoard XD - Realtime visitor statistics page
//  Access: all

$title = __("Online users");

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Online users"), "online"));
makeBreadcrumbs($crumbs);

AssertForbidden("viewOnline");

// This can (and will) be turned into a permission.
$showIPs = $loguser['powerlevel'] > 0;

$time = (int)$_GET['time'];
if(!$time) $time = 300;

$rUsers = Query("select * from {users} where lastactivity > {0} order by lastactivity desc", (time()-$time));
$rGuests = Query("select * from {guests} where date > {0} and bot = 0 order by date desc", (time()-$time));
$rBots = Query("select * from {guests} where date > {0} and bot = 1 order by date desc", (time()-$time));

$spans = array(60, 300, 900, 3600, 86400);
$spanList = "";
foreach($spans as $span)
{
	$spanList .= actionLinkTagItem(timeunits($span), "online", "", "time=$span");
}
write(
"
	<div class=\"smallFonts margin\">
		".__("Show visitors from this far back:")."
		<ul class=\"pipemenu\">
			{0}
		</ul>
	</div>
", $spanList);


$userList = "";
$i = 1;
if(NumRows($rUsers))
{
	while($user = Fetch($rUsers))
	{
		$cellClass = ($cellClass+1) % 2;
		if($user['lasturl']) {
			if($user['lasturlminpower'] > $loguser['powerlevel'])
				$lastUrl = __("In a restricted area.");
			else
				$lastUrl = "<a href=\"".FilterURL($user['lasturl'])."\">".FilterURL($user['lasturl'])."</a>";
		}
		else
			$lastUrl = __("None");

		$userList .= "
		<tr class=\"cell$cellClass\">
			<td>$i</td>
			<td>".UserLink($user)."</td>
			<td>".($user['lastposttime'] ? cdate("d-m-y G:i:s",$user['lastposttime']) : __("Never"))."</td>
			<td>".cdate("d-m-y G:i:s", $user['lastactivity'])."</td>
			<td>$lastUrl</td>";
		if($showIPs) $userList .= "<td>".formatIP($user['lastip'])."</td>";
		$userList .= "</tr>";

		$i++;
	}
}
else
	$userList = "<tr class=\"cell0\"><td colspan=\"6\">".__("No users")."</td></tr>";



function listGuests($rGuests, $noMsg)
{
	global $showIPs;

	if(!NumRows($rGuests))
		return "<tr class=\"cell0\"><td colspan=\"6\">$noMsg</td></tr>";

	$i = 1;
	while($guest = Fetch($rGuests))
	{
		$cellClass = ($cellClass+1) % 2;
		if($guest['date'])
			$lastUrl = "<a href=\"".FilterURL($guest['lasturl'])."\">".FilterURL($guest['lasturl'])."</a>";
		else
			$lastUrl = __("None");

		$guestList .= "
		<tr class=\"cell$cellClass\">
			<td>$i</td>
			<td colspan=\"2\" title=\"".htmlspecialchars($guest['useragent'])."\">".htmlspecialchars(substr($guest['useragent'], 0, 65))."</td>
			<td>".cdate("d-m-y G:i:s", $guest['date'])."</td>
			<td>$lastUrl</td>";
		if($showIPs) $guestList .= "<td>".formatIP($guest['ip'])."</td>";
		$guestList .= "</tr>";

		$i++;
	}

	return $guestList;
}

$guestList = listGuests($rGuests, __("No guests"));
$botList = listGuests($rBots, __("No bots"));

write(
"
	<table class=\"outline margin\">
		<tr class=\"header0\">
			<th colspan=\"6\">
				".__("Online users")."
			</th>
		</tr>
		<tr class=\"header1\">
			<th style=\"width: 30px;\">
				#
			</th>
			<th>
				".__("Name")."
			</th>
			<th style=\"width: 140px;\">
				".__("Last post")."
			</th>
			<th style=\"width: 140px;\">
				".__("Last view")."
			</th>
			<th>
				".__("URL")."
			</th>
".($showIPs ? "
			<th style=\"width: 140px;\">
				".__("IP")."
			</th>
" : "")."
		</tr>
		{0}

		<tr class=\"header0\">
			<th colspan=\"6\">
				".__("Guests")."
			</th>
		</tr>
		{1}
		<tr class=\"header0\">
			<th colspan=\"6\">
				".__("Bots")."
			</th>
		</tr>
		{2}
	</table>
", $userList, $guestList, $botList);

function FilterURL($url)
{
	$url = str_replace('_', ' ', urldecode($url));
	$url = htmlspecialchars($url);
	$url = preg_replace("@&?(key|token)=[0-9a-f]{40,64}@i", '', $url);
	return $url;
}

?>
