<?php
//  AcmlmBoard XD - Private message inbox/outbox viewer
//  Access: users

AssertForbidden("viewPM");

$title = "Private messages";

if(!$loguserid)
	Kill(__("You must be logged in to view your private messages."));

$user = $loguserid;
if(isset($_GET['id']) && $loguser['powerlevel'] > 2)
{
	$user = (int)$_GET['id'];
	$snoop = "&snooping=1";
	$userGet = $user;
}
else
	$userGet = "";

if(isset($_POST['action']))
{
	if ($_POST['token'] !== $loguser['token']) Kill('No.');
	
	if($_POST['action'] == "multidel" && $_POST['delete'] && $snoop != 1)
	{
		$deleted = 0;
		foreach($_POST['delete'] as $pid => $on)
		{
			$rPM = Query("select * from {pmsgs} where id = {0} and (userto = {1} or userfrom = {1})", $pid, $loguserid);
			if(NumRows($rPM))
			{
				$pm = Fetch($rPM);
				$val = $pm['userto'] == $loguserid ? 2 : 1;
				$newVal = ($pm['deleted'] | $val);
				if($newVal == 3)
				{
					Query("delete from {pmsgs} where id = {0}", $pid);
					Query("delete from {pmsgs_text} where pid = {0}", $pid);
				}
				else
					Query("update {pmsgs} set deleted = {0} where id = {1}", $newVal, $pid);
				$deleted++;
			}
		}
		Alert(format(__("{0} deleted."), Plural($deleted, __("private message"))));
	}
}

if(isset($_GET['del']))
{
	if ($_GET['token'] !== $loguser['token']) Kill('No.');
	
	$pid = (int)$_GET['del'];
	$rPM = Query("select * from {pmsgs} where id = {0} and (userto = {1} or userfrom = {1})", $pid, $loguserid);
	if(NumRows($rPM))
	{
		$pm = Fetch($rPM);
		$val = $pm['userto'] == $loguserid ? 2 : 1;
		$newVal = ($pm['deleted'] | $val);
		if($newVal == 3)
		{
			Query("delete from {pmsgs} where id = {0}", $pid);
			Query("delete from {pmsgs_text} where pid = {0}", $pid);
		}
		else
			Query("update {pmsgs} set deleted = {0} where id = {1}", $newVal, $pid);
		Alert(__("Private message deleted."));
	}
}

$whereFrom = "userfrom = {0}";
$drafting = 0;
$deleted = 2;
if(isset($_GET['show']))
{
	$show = "&show=".(int)$_GET['show'];
	if($_GET['show'] == 1)
		$deleted = 1;
	else if($_GET['show'] == 2)
		$drafting = 1;
}
else
{
	$whereFrom = "userto = {0}";
}
$whereFrom .= " and drafting = ".$drafting;

$total = FetchResult("select count(*) from {pmsgs} where {$whereFrom} and deleted != {1}", $user, $deleted);

$ppp = $loguser['postsperpage'];

if(isset($_GET['from']))
	$from = (int)$_GET['from'];
else
	$from = 0;


$links = new PipeMenu();
$links -> add(new PipeMenuLinkEntry(__("Show received"), "private", $userGet, "", "download-alt"));
$links -> add(new PipeMenuLinkEntry(__("Show sent"), "private", $userGet, "show=1", "upload-alt"));
$links -> add(new PipeMenuLinkEntry(__("Show drafts"), "private", $userGet, "show=2", "save"));
$links -> add(new PipeMenuLinkEntry(__("Send new PM"), "sendprivate", "", "", "plus"));

makeLinks($links);

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Member list"), "memberlist"));
$crumbs->add(new PipeMenuHtmlEntry(userLinkById($user)));
$crumbs->add(new PipeMenuLinkEntry(__("Private messages"), "private", $userGet));
makeBreadcrumbs($crumbs);

$rPM = Query("select * from {pmsgs} left join {pmsgs_text} on pid = {pmsgs}.id where ".$whereFrom." and deleted != {1} order by date desc limit {2u}, {3u}", $user, $deleted, $from, $ppp);
$numonpage = NumRows($rPM);

$pagelinks = PageLinks(actionLink("private", "", "$show$userGet&from="), $ppp, $from, $total);

if($pagelinks)
	write("<div class=\"smallFonts pages\">".__("Pages:")." {0}</div>", $pagelinks);

if(NumRows($rPM))
{
	while($pm = Fetch($rPM))
	{
		$rUser = Query("select * from {users} where id = {0}", (isset($_GET['show']) ? $pm['userto'] : $pm['userfrom']));
		if(NumRows($rUser))
			$user = Fetch($rUser);

		$cellClass = ($cellClass+1) % 2;
		if(!$pm['msgread'])
			$img = "<img src=\"".resourceLink("img/status/new.png")."\" alt=\"New!\" />";
		else
			$img = "";

		$sender = (NumRows($rUser) ? UserLink($user) : "_");

		$check = $snoop ? "" : "<input type=\"checkbox\" name=\"delete[{2}]\" />";

		$delLink = $snoop == "" ? "<sup>&nbsp;".actionLinkTag("&#x2718;", "private", "", "del=".$pm['id'].$show.'&token='.$loguser['token'])."</sup>" : "";

		$pms .= format(
"
		<tr class=\"cell{0}\">
			<td>
				".$check."
			</td>
			<td class=\"center\">
				{1}
			</td>
			<td>
				".actionLinkTag(htmlspecialchars($pm['title']), "showprivate", $pm['id'], $snoop)."{7}
			</td>
			<td>
				{5}
			</td>
			<td>
				{6}
			</td>
		</tr>
",	$cellClass, $img, $pm['id'], $snoop, htmlspecialchars($pm['title']), $sender, formatdate($pm['date']), $delLink);
	}
}
else
	$pms = format(
"
		<tr class=\"cell1\">
			<td colspan=\"6\">
				".__("There are no messages to display.")."
			</td>
		</tr>
");

write(
"
	<form method=\"post\" action=\"".actionLink("private")."\">
	<table class=\"outline margin\">
		<tr class=\"header1\">
			<th style=\"width: 22px;\">
				<input type=\"checkbox\" id=\"ca\" onchange=\"checkAll();\" />
			</th>
			<th style=\"width: 22px;\">&nbsp;</th>
			<th style=\"width: 75%;\">".__("Title")."</th>
			<th>{0}</th>
			<th style=\"min-width:120px\">".__("Date")."</th>
		</tr>
		{1}
		<tr class=\"header1\">
			<th style=\"text-align: right;\" colspan=\"6\">
				<input type=\"hidden\" name=\"action\" value=\"multidel\" />
				<input type=\"hidden\" name=\"token\" value=\"{$loguser['token']}\" />
				<a href=\"javascript:void();\" onclick=\"document.forms[1].submit();\">".__("delete checked")."</a>
			</th>
		</tr>
	</table>
	</font>
", (isset($_GET['show']) ? __("To") : __("From")), $pms);

if($pagelinks)
	write("<div class=\"smallFonts pages\">".__("Pages:")." {0}</div>", $pagelinks);

?>
