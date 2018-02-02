<?php

$title = __("Uploader");

AssertForbidden("viewUploader");

if($uploaderWhitelist)
	$goodfiles = explode(" ", $uploaderWhitelist);

$badfiles = array("html", "htm", "php", "php2", "php3", "php4", "php5", "php6", "htaccess", "htpasswd", "mht", "js", "asp", "aspx", "cgi", "py", "exe", "com", "bat", "pif", "cmd", "lnk", "wsh", "vbs", "vbe", "jse", "wsf", "msc", "pl", "rb", "shtm", "shtml", "stm", "htc");

function listCategory($cat)
{
	global $loguser, $loguserid, $dataDir, $userSelectUsers, $boardroot;

	if(isset($_GET['sort']) && $_GET['sort'] == "name" || $_GET['sort'] == "date")
		$skey = $_GET['sort'];
	else
		$skey = "date";

	$sortOptions = "<div class=\"margin smallFonts\">".__("Sort order").": <ul class=\"pipemenu\">";
	$sortOptions .= ($skey == "name")
			?"<li>".__("Name")."</li>"
			:actionLinkTagItem(__("Name"), "uploaderlist", "", "cat=${_GET["cat"]}&sort=name");
	$sortOptions .= ($skey == "date")
			?"<li>".__("Date")."</li>"
			:actionLinkTagItem(__("Date"), "uploaderlist", "", "cat=${_GET["cat"]}&sort=date");
	$sortOptions .= "</ul></div>";
	$sdir = ($skey == "date") ? " desc" : " asc";


	print $sortOptions;

	if($cat == -1)
		$condition = "f.user = ".$loguserid." and up.private = 1";
	else if($cat == -2 && $loguser['powerlevel'] > 2)
		$condition = "up.private = 1";
	else
		$condition = "up.private = 0 and up.category = {0}";

	$errormsg = __("The category is empty.");
	if($cat < 0)
		$errormsg = __("You have no private files.");

	$entries = Query("SELECT
			up.id, f.name, f.hash, up.description, f.downloads,
			u.(_userfields)
			FROM {uploader} up
			JOIN {files} f on up.id = f.id
			LEFT JOIN {users} u on f.user = u.id
			WHERE $condition
			ORDER BY ".$skey.$sdir, $cat);

	$checkbox = "";
	if($loguserid)
	{
		$checkbox = "<input type=\"checkbox\" id=\"ca\" onchange=\"checkAll();\" />";
		$checkbox = "<th style=\"width: 22px;\">$checkbox</th>";
	}

	if(NumRows($entries) == 0)
	{
		print "
		<table class=\"outline margin\">
			<tr class=\"header0\">
				<th colspan=\"7\">".__("Files")."</th>
			</tr>
			<tr class=\"cell1\">
				<td colspan=\"4\">
					".$errormsg."
				</td>
			</tr>
		</table>
		";
	}
	else
	{
		print
		"
		<table class=\"outline margin\">
			<tr class=\"header0\">
				<th colspan=\"7\">".__("Files")."</th>
			</tr>

		";

		print 	"
			<tr class=\"header1\">
				$checkbox
				<th>
					".__("File")."
				</th>
				<th>
					".__("Description")."
				</th>
				<th>
					".__("Size")."
				</th>
				<th>
					".__("Uploader")."
				</th>
				<th>
					".__("Downloads")."
				</th>
			</tr>
		";

		while($entry = Fetch($entries))
		{
			$delete = "";
			$multidel = "";
			if($loguserid)
				$multidel = "<td><input type=\"checkbox\" name=\"delete[".$entry['id']."]\" disabled=\"disabled\" /></td>";
			if($loguserid == $entry['user'] || $loguser['powerlevel'] > 2)
			{
				$delete = "&nbsp;<sup>"
					.actionLinkTagUnescaped("&#x2718;", "uploader", "", "action=delete&fid=".$entry['id']."&cat=".$_GET["cat"])
					."</sup>";
				$multidel = "<td><input type=\"checkbox\" name=\"del[".$entry['id']."]\" /></td>";
			}
			$cellClass = ($cellClass+1) % 2;

			$filepath = $dataDir."uploads/".substr($entry['hash'], 0, 2).'/'.$entry['hash'];

			print format(
			"
			<tr class=\"cell{0}\">
				{7}
				<td>
                    <a href=\"{$boardroot}file/{1}/{2}\">{2}</a>{3}
				</td>
				<td>
					{4}
				</td>
				<td>
					{5}
				</td>
				<td>
					{6}
				</td>
				<td>
					{8}
				</td>
			</tr>
			",	$cellClass, $entry['id'], htmlspecialchars($entry['name']), $delete, htmlspecialchars($entry['description']),
				BytesToSize(@filesize($filepath)), UserLink(getDataPrefix($entry, "u_")), $multidel, $entry["downloads"]);
		}


		if($loguserid)
		{
			$entries = Query("select * from {uploader_categories} order by ord");
			$movelist = "";

			while($entry = Fetch($entries))
			{
				$movelist .= "<option value='${entry["id"]}'>${entry["name"]}</option>";
			}
			$movelist = "<select name='destcat' size='1'>$movelist</select>";

			print format("
				<tr class=\"header1\">
					<th style=\"text-align: right;\" colspan=\"6\">
						<input type=\"hidden\" id='actionfield' name=\"action\" value=\"multidel\" />
						<a href=\"javascript:void();\" onclick=\"document.getElementById('actionfield').value = 'multidel'; document.forms[1].submit();\">".__("delete checked")."</a>
						<a href=\"javascript:void();\" onclick=\"document.getElementById('actionfield').value = 'multimove'; document.forms[1].submit();\">".__("Move checked to")."</a>$movelist
					</th>
				</tr>");
		}
			print "</table>";
	}
}


$cat = getCategory($_GET["cat"]);

$links = new PipeMenu();
if($_GET["cat"] != -2 && $loguserid && !$isBot)
	$links -> add(new PipeMenuLinkEntry("Upload file", "uploader", "", "action=uploadform&cat=".$_GET["cat"], "cloud-upload"));
makeLinks($links);

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Uploader"), "uploader"));
$crumbs->add(new PipeMenuLinkEntry($cat["name"], "uploaderlist", "", "cat=".$cat["id"]));
makeBreadcrumbs($crumbs);

print "<form method=\"post\" action=\"".actionLink("uploader", "", "cat=${_GET["cat"]}")."\">";
listCategory($_GET["cat"]);
print "</form>";


function getCategory($cat)
{
	if (!is_numeric($cat))
		Kill('Invalid category');

	if($cat >= 0)
	{
		$rCategory = Query("select * from {uploader_categories} where id={0}", $cat);
		if(NumRows($rCategory) == 0) Kill("Invalid category");
		$rcat = Fetch($rCategory);
	}
	else if($cat == -1)
		$rcat = array("id" => -1, "name" => "Private files");
	else if($cat == -2)
		$rcat = array("id" => -2, "name" => "All private files");
	else
		Kill('Invalid category');

	return $rcat;
}

?>
