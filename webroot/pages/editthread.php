<?php
//  AcmlmBoard XD - Thread editing page
//  Access: moderators

$title = __("Edit thread");

AssertForbidden("editThread");

if (isset($_REQUEST['action']) && $loguser['token'] != $_REQUEST['key'])
		Kill(__("No."));

if(!$loguserid) //Not logged in?
	Kill(__("You must be logged in to edit threads."));

if(isset($_POST['id']))
	$_GET['id'] = $_POST['id'];

if(!isset($_GET['id']))
	Kill(__("Thread ID unspecified."));

$tid = (int)$_GET['id'];

$rThread = Query("select * from {threads} where id={0}", $tid);
if(NumRows($rThread))
	$thread = Fetch($rThread);
else
	Kill(__("Unknown thread ID."));

$canMod = CanMod($loguserid, $thread['forum']);

if(!$canMod && $thread['user'] != $loguserid)
	Kill(__("You are not allowed to edit threads."));

if(!$canMod && $thread['closed'])
	Kill(__("You are not allowed to edit closed threads."));

$OnlineUsersFid = $thread['forum'];

$fid = $thread["forum"];
$rFora = Query("select id, minpower, title, catid from {forums} where id={0}", $fid);

if(NumRows($rFora))
	$forum = Fetch($rFora);
else
	Kill(__("Unknown forum ID."));

if($forum['minpower'] > $loguser['powerlevel'])
	Kill(__("You are not allowed to edit threads."));
$tags = ParseThreadTags($thread['title']);
setUrlName("thread", $thread["id"], $thread["title"]);

$crumbs = new PipeMenu();
makeForumCrumbs($crumbs, $forum);
$crumbs->add(new PipeMenuHtmlEntry(makeThreadLink($thread)));
$crumbs->add(new PipeMenuTextEntry(__("Edit thread")));
makeBreadcrumbs($crumbs);

if(isset($_POST["action"]))
	$_GET["action"] = $_POST["action"];

if($canMod)
{
	if($_GET['action'] == "close")
	{
		$rThread = Query("update {threads} set closed=1 where id={0}", $tid);
		logAction('closethread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));

		redirectAction("thread", $tid);
	}
	elseif($_GET['action'] == "open")
	{
		$rThread = Query("update {threads} set closed=0 where id={0}", $tid);
		logAction('openthread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));

		redirectAction("thread", $tid);
	}
	elseif($_GET['action'] == "stick")
	{
		$rThread = Query("update {threads} set sticky=1 where id={0}", $tid);
		logAction('stickthread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));

		redirectAction("thread", $tid);
	}
	elseif($_GET['action'] == "unstick")
	{
		$rThread = Query("update {threads} set sticky=0 where id={0}", $tid);
		logAction('unstickthread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));

		redirectAction("thread", $tid);
	}

	// Move thread!
	if($_GET['action'] == "edit" || $_GET['action'] == "delete" || $_GET['action'] == "trash")
	{
		if($_GET["action"] == "trash")
			$_POST["moveTo"] = Settings::get("trashForum");
		if($_GET["action"] == "delete")
			$_POST["moveTo"] = Settings::get("hiddenTrashForum");

		if($thread["forum"] != $_POST["moveTo"])
		{
			$moveto = (int)$_POST['moveTo'];
			$dest = Fetch(Query("select * from {forums} where id={0}", $moveto));
			if(!$dest)
			{
				if($_GET['action'] == "delete")
					Kill(__("Couldn't find deleted thread forum. Please specify one in the board's settings."));
				else if($_GET['action'] == "trash")
					Kill(__("Couldn't find trash forum. Please specify one in the board's settings."));
				else
					Kill(__("Unknown forum ID."));
			}

			//Tweak forum counters
			$rForum = Query("update {forums} set numthreads=numthreads-1, numposts=numposts-{0} where id={1}",
							$thread['replies']+1, $thread['forum']);

			$rForum = Query("update {forums} set numthreads=numthreads+1, numposts=numposts+{0} where id={1}",
							$thread['replies']+1, $moveto);

			$rThread = Query("update {threads} set forum={0} where id={1}",
							(int)$_POST['moveTo'], $tid);

			// Tweak forum counters #2
			Query("	UPDATE {forums} LEFT JOIN {threads}
					ON {forums}.id={threads}.forum AND {threads}.lastpostdate=(SELECT MAX(nt.lastpostdate) FROM {threads} nt WHERE nt.forum={forums}.id)
					SET {forums}.lastpostdate=IFNULL({threads}.lastpostdate,0), {forums}.lastpostuser=IFNULL({threads}.lastposter,0), {forums}.lastpostid=IFNULL({threads}.lastpostid,0)
					WHERE {forums}.id={0} OR {forums}.id={1}", $thread['forum'], $moveto);

			if($_GET['action'] == "delete")
				logAction('deletethread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));
			else if($_GET['action'] == "trash")
				logAction('trashthread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));
			else
				logAction('movethread', array('forum' => $fid, 'thread' => $tid, 'forum2' => $moveto, 'user2' => $thread["user"]));

			recalculateKarma($thread["user"]);
		}
	}

	//Close and unstick thread if deleting or trashing.
	if($_GET['action'] == "delete" || $_GET['action'] == "trash")
	{
		$rThread = Query("update {threads} set sticky=0, closed=1 where id={0}", $tid);
		redirectAction("forum", $fid);
	}

	//Editpost open/close.
	if($_GET['action'] == "edit")
	{
		$isClosed = (isset($_POST['isClosed']) ? 1 : 0);
		$isSticky = (isset($_POST['isSticky']) ? 1 : 0);

		if(!$thread["sticky"] && $isSticky)
			logAction('stickthread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));
		if($thread["sticky"] && !$isSticky)
			logAction('unstickthread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));
		if(!$thread["closed"] && $isClosed)
			logAction('closethread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));
		if($thread["closed"] && !$isClosed)
			logAction('openthread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));

		Query("update {threads} set closed={0}, sticky={1} where id={2} limit 1", $isClosed, $isSticky, $tid);
	}
}

//Edit thread title and icon. Both mods AND thread-owners can do this.
if($_GET['action'] == "edit")
{
	$trimmedTitle = trim(str_replace('&nbsp;', ' ', $_POST['title']));
	if($trimmedTitle != "")
	{
		$iconurl = '';
		if($_POST['iconid'])
		{
			$_POST['iconid'] = (int)$_POST['iconid'];
			if($_POST['iconid'] < 255)
				$iconurl = "img/icons/icon".$_POST['iconid'].".png";
			else
				$iconurl = $_POST["iconurl"];
		}

		if($thread["title"] != $_POST['title'] || $thread["icon"] != $iconurl)
			logAction('editthread', array('forum' => $fid, 'thread' => $tid, 'user2' => $thread["user"]));

		$rThreads = Query("update {threads} set title={0}, icon={1} where id={2} limit 1", $_POST['title'], $iconurl, $tid);

		redirectAction("thread", $tid);
	}
	else
		Alert(__("Your thread title is empty. Enter a message and try again."));
}


//Fetch thread again in case something above has changed.
$rThread = Query("select * from {threads} where id={0}", $tid);
if(NumRows($rThread))
	$thread = Fetch($rThread);
else
	Kill(__("Unknown thread ID."));

$canMod = CanMod($loguserid, $thread['forum']);

if(!$canMod && $thread['user'] != $loguserid)
	Kill(__("You are not allowed to edit threads."));

$OnlineUsersFid = $thread['forum'];

$fid = $thread["forum"];
$rFora = Query("select id, minpower, title from {forums} where id={0}", $fid);

if(NumRows($rFora))
	$forum = Fetch($rFora);
else
	Kill(__("Unknown forum ID."));

if($forum['minpower'] > $loguser['powerlevel'])
	Kill(__("You are not allowed to edit threads."));


//Recover data from POST
if(!isset($_POST['title']))
	$_POST['title'] = $thread['title'];

if(!isset($_POST["iconid"]))
{
	$match = array();
	if (preg_match("@^img/icons/icon(\d+)\..{3,}\$@si", $thread['icon'], $match))
		$_POST['iconid'] = $match[1];
	elseif($thread['icon'] == "") //Has no icon
		$_POST['iconid'] = 0;
	else //Has custom icon
	{
		$_POST['iconid'] = 255;
		$_POST['iconurl'] = $thread['icon'];
	}
}


//ICONS!

$icons = "";
$i = 1;
while(is_file("img/icons/icon".$i.".png"))
{
	$check = "";
	if($_POST['iconid'] == $i) $check = "checked=\"checked\" ";
	$icons .= "	<label>
					<input type=\"radio\" $check name=\"iconid\" value=\"$i\" />
					<img src=\"".resourceLink("img/icons/icon$i.png")."\" alt=\"Icon $i\" onclick=\"javascript:void()\" />
				</label>";
	$i++;
}
$check[0] = "";
$check[1] = "";
if($_POST['iconid'] == 0) $check[0] = "checked=\"checked\" ";
if($_POST['iconid'] == 255)
{
	$check[1] = "checked=\"checked\" ";
	$iconurl = $_POST['iconurl'];
}

if($canMod)
{
	echo "
	<script src=\"".resourceLink("js/threadtagging.js")."\"></script>
	<form action=\"".actionLink("editthread")."\" method=\"post\">
		<table class=\"outline margin\" style=\"width: 100%;\">
			<tr class=\"header1\">
				<th colspan=\"2\">
					".__("Edit thread")."
				</th>
			</tr>
			<tr class=\"cell0\">
				<td>
					<label for=\"tit\">".__("Title")."</label>
				</td>
				<td id=\"threadTitleContainer\">
					<input type=\"text\" id=\"tit\" name=\"title\" style=\"width: 98%;\" maxlength=\"60\" value=\"".htmlspecialchars($_POST['title'])."\" />
				</td>
			</tr>
			<tr class=\"cell1\">
				<td>
					".__("Icon")."
				</td>
				<td class=\"threadIcons\">
					<label>
						<input type=\"radio\" {$check[0]} id=\"noicon\" name=\"iconid\" value=\"0\">
						".__("None")."
					</label>
					$icons
					<br/>
					<label>
						<input type=\"radio\" {$check[1]} name=\"iconid\" value=\"255\" />
						<span>".__("Custom")."</span>
					</label>
					<input type=\"text\" name=\"iconurl\" style=\"width: 50%;\" maxlength=\"100\" value=\"".htmlspecialchars($iconurl)."\" />
				</td>
			</tr>
			<tr class=\"cell0\">
				<td>
					".__("Extras")."
				</td>
				<td>
					<label>
						<input type=\"checkbox\" name=\"isClosed\" ".($thread['closed'] ? " checked=\"checked\"" : "")." />
						".__("Closed")."
					</label>
					<label>
						<input type=\"checkbox\" name=\"isSticky\" ".($thread['sticky'] ? " checked=\"checked\"" : "")." />
						".__("Sticky")."
					</label>
				</td>
			</tr>
			<tr class=\"cell1\">
				<td>
					".__("Move")."
				</td>
				<td>
					".makeForumList('moveTo', $thread["forum"])."
				</td>
			</tr>
			<tr class=\"cell2\">
				<td></td>
				<td>
					<input type=\"submit\" name=\"asdf\" value=\"".__("Edit")."\" />
					<input type=\"hidden\" name=\"id\" value=\"$tid\" />
					<input type=\"hidden\" name=\"key\" value=\"${loguser["token"]}\" />
					<input type=\"hidden\" name=\"action\" value=\"edit\" />
				</td>
			</tr>
		</table>
	</form>";
}
else
{
	echo "
	<script src=\"".resourceLink("js/threadtagging.js")."\"></script>
	<form action=\"".actionLink("editthread")."\" method=\"post\">
		<table class=\"outline margin width50\">
			<tr class=\"cell0\">
				<td>
					<label for=\"tit\">".__("Title")."</label>
				</td>
				<td>
					<input type=\"text\" id=\"tit\" name=\"title\" style=\"width: 98%;\" maxlength=\"60\" value=\"".htmlspecialchars($_POST['title'])."\" />
				</td>
			</tr>
			<tr class=\"cell1\">
				<td>
					".__("Icon")."
				</td>
				<td class=\"threadIcons\">
					<label>
						<input type=\"radio\" {$check[0]} id=\"noicon\" name=\"iconid\" value=\"0\">
						".__("None")."
					</label>
					$icons
					<br/>
					<label>
						<input type=\"radio\" {$check[1]} name=\"iconid\" value=\"255\" />
						<span>".__("Custom")."</span>
					</label>
					<input type=\"text\" name=\"iconurl\" style=\"width: 50%;\" maxlength=\"100\" value=\"".htmlspecialchars($iconurl)."\" />
				</td>
			</tr>
			<tr class=\"cell2\">
				<td></td>
				<td>
					<input type=\"submit\" name=\"asdf\" value=\"".__("Edit")."\" />
					<input type=\"hidden\" name=\"id\" value=\"$tid\" />
					<input type=\"hidden\" name=\"key\" value=\"${loguser["token"]}\" />
					<input type=\"hidden\" name=\"action\" value=\"edit\" />
				</td>
			</tr>
		</table>
	</form>";
}

?>
