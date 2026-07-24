<?php
//  AcmlmBoard XD - IP ban management tool
//  Access: administrators only

$title = __("IP bans");

AssertForbidden("editIPBans");

if($loguser['powerlevel'] < 3)
	Kill(__("Only administrators get to manage IP bans."));

$lastUrlMinPower = 3;

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Admin"), "admin"));
$crumbs->add(new PipeMenuLinkEntry(__("IP bans"), "ipbans"));
makeBreadcrumbs($crumbs);

if(isset($_POST['actionadd']) || isset($_POST['actiondelete']))
{
	// Both the add and the delete below change state, so they need the token.
	// It rides in the forms, so the ban list keeps clean URLs.
	if(!isset($_POST['key']) || !hash_equals($loguser['token'], $_POST['key']))
		Kill(__("No."));
}

if(isset($_POST['actionadd']))
{
	//This doesn't allow you to ban IP ranges...
	//if(!filter_var($_POST['ip'], FILTER_VALIDATE_IP))
	//	Alert("Invalid IP");
	//else
	if(isIPBanned($_POST['ip']))
		Alert("Already banned IP!");
	else
	{
		$whitelist = $_POST['whitelisted'] ? 'TRUE' : 'FALSE';
		$rIPBan = Query("insert into {ipbans} (ip, reason, date, whitelisted, addedby, dateadded) values ({0}, {1}, {2}, $whitelist, {3}, {4})", $_POST['ip'], $_POST['reason'], ((int)$_POST['days'] > 0 ? time() + ((int)$_POST['days'] * 86400) : 0), $loguser['id'], time());
		Alert(__("Added."), __("Notice"));
	}
}
elseif(isset($_POST['actiondelete']))
{
	$rIPBan = Query("delete from {ipbans} where ip={0} limit 1", $_POST['ip']);
	Alert(__("Removed."), __("Notice"));
}

// Sortable columns: the key is what shows up in the URL, the value is what
// goes into the ORDER BY. Never take the sort straight from the query string.
$sortFields = array(
	"ip" => "b.ip",
	"reason" => "b.reason",
	"expiry" => "b.date",
	"user" => "if(u.displayname != '', u.displayname, u.name)",
	"added" => "b.dateadded",
	"whitelisted" => "b.whitelisted",
);
//Text sorts read better ascending, dates newest-first.
$sortDefaultDirs = array(
	"ip" => "asc",
	"reason" => "asc",
	"expiry" => "desc",
	"user" => "asc",
	"added" => "desc",
	"whitelisted" => "desc",
);

$sort = $_GET['sort'];
if(!isset($sortFields[$sort]))
	$sort = "added";

$dir = $_GET['dir'];
if($dir != "asc" && $dir != "desc")
	$dir = $sortDefaultDirs[$sort];

function ipBanSortHeader($label, $key)
{
	global $sort, $dir, $sortDefaultDirs;

	if($sort == $key)
	{
		$newDir = ($dir == "asc" ? "desc" : "asc");
		$arrow = " ".($dir == "asc" ? "&#x25B2;" : "&#x25BC;");
	}
	else
	{
		$newDir = $sortDefaultDirs[$key];
		$arrow = "";
	}

	$url = htmlspecialchars(actionLink("ipbans", "", "sort=$key&dir=$newDir"));
	return "<a href=\"$url\">".$label."</a>".$arrow;
}

//Ties broken by IP so the order stays stable when the sort key repeats.
$rIPBan = Query("select b.*, u.(_userfields)
	from {ipbans} b
	left join {users} u on u.id = b.addedby
	order by ".$sortFields[$sort]." ".$dir.", b.ip asc");

$banList = "";
while($ipban = Fetch($rIPBan))
{
	$cellClass = ($cellClass+1) % 2;
	if($ipban['date'])
		$date = formatdate($ipban['date'])." (".TimeUnits($ipban['date']-time())." left)";
	else
		$date = __("Permanent");

	if($ipban['u_id'])
		$addedBy = UserLink(getDataPrefix($ipban, "u_"));
	else
		$addedBy = __("Unknown");

	if($ipban['dateadded'])
		$dateAdded = formatdate($ipban['dateadded']);
	else
		$dateAdded = __("Unknown");

	$banList .= "
	<tr class=\"cell$cellClass\">
		<td>".htmlspecialchars($ipban['ip'])."</td>
		<td>".htmlspecialchars($ipban['reason'])."</td>
		<td>$date</td>
		<td>$addedBy</td>
		<td>$dateAdded</td>
		<td>".($ipban['whitelisted'] ? "Yes" : "No")."
		<td>
			<form action=\"".actionLink("ipbans")."\" method=\"post\" style=\"display: inline;\">
				<input type=\"hidden\" name=\"key\" value=\"".htmlspecialchars($loguser['token'])."\" />
				<input type=\"hidden\" name=\"ip\" value=\"".htmlspecialchars($ipban['ip'])."\" />
				<button type=\"submit\" name=\"actiondelete\" value=\"1\" style=\"border: none; background: none; padding: 0; cursor: pointer; color: inherit; font: inherit;\">&#x2718;</button>
			</form>
		</td>
	</tr>";
}

print "
<table class=\"outline margin width50\">
	<tr class=\"header1\">
		<th>".ipBanSortHeader(__("IP"), "ip")."</th>
		<th>".ipBanSortHeader(__("Reason"), "reason")."</th>
		<th>".ipBanSortHeader(__("Date"), "expiry")."</th>
		<th>".ipBanSortHeader(__("Added by"), "user")."</th>
		<th>".ipBanSortHeader(__("Added on"), "added")."</th>
		<th>".ipBanSortHeader(__("Whitelisted"), "whitelisted")."</th>
		<th>&nbsp;</th>
	</tr>
	$banList
</table>

<form action=\"".actionLink("ipbans")."\" method=\"post\">
	<input type=\"hidden\" name=\"key\" value=\"".htmlspecialchars($loguser['token'])."\" />
	<table class=\"outline margin width50\">
		<tr class=\"header1\">
			<th colspan=\"2\">
				".__("Add")."
			</th>
		</tr>
		<tr>
			<td class=\"cell2\">
				".__("IP")."
			</td>
			<td class=\"cell0\">
				<input type=\"text\" name=\"ip\" style=\"width: 98%;\" maxlength=\"45\" />
			</td>
		</tr>
		<tr>
			<td class=\"cell2\">
				".__("Reason")."
			</td>
			<td class=\"cell1\">
				<input type=\"text\" name=\"reason\" style=\"width: 98%;\" maxlength=\"100\" />
			</td>
		</tr>
		<tr>
			<td class=\"cell2\">
				".__("For")."
			</td>
			<td class=\"cell1\">
				<input type=\"text\" name=\"days\" size=\"13\" maxlength=\"13\" /> ".__("days")."
			</td>
		</tr>
        <tr>
            <td class=\"cell2\">
                ".__("Whitelisted")."
            </td>
            <td class=\"cell1\">
                <input type=\"checkbox\" name=\"whitelisted\" size=\"13\" maxlength=\"13\" />
            </td>
        </tr>
		<tr class=\"cell2\">
			<td></td>
			<td>
				<input type=\"submit\" name=\"actionadd\" value=\"".__("Add")."\" />
			</td>
		</tr>
	</table>
</form>";


?>
