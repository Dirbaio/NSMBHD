<?php

$ajaxPage = true;

$id = (int)$_GET["id"];

$qPost = "select p.currentrevision, p.thread, p.user, f.id fid, f.minpower
			from {posts} p
			left join {threads} t on t.id=p.thread
			left join {forums} f on f.id=t.forum
			where p.id={0}";
$rPost = Query($qPost, $id);
if(NumRows($rPost))
	$post = Fetch($rPost);
else
	die(format(__("Unknown post ID #{0}."), $id)." ".$hideTricks);

if($post['minpower'] > $loguser['powerlevel'])
	die(__("No.")." ".$hideTricks);

// Same gate as getpost.php: the edit history is for the author and the
// moderators only, not for everyone who can see the forum.
if(!CanMod($loguserid, $post['fid']) && $loguserid != $post['user'])
	die(__("No.")." ".$hideTricks);


$qRevs = "SELECT
			revision, date AS revdate,
			ru.(_userfields)
		FROM
			{posts_text}
			LEFT JOIN {users} ru ON ru.id = user
		WHERE pid={0}
		ORDER BY revision ASC";
$revs = Query($qRevs, $id);


$reply = __("Show revision:")."<br>";
while($revision = Fetch($revs))
{
	$reply .= " <a href=\"javascript:void(0)\" onclick=\"showRevision(".$id.",".$revision["revision"].")\">".format(__("rev. {0}"), $revision["revision"])."</a>";

	if ($revision['ru_id'])
	{
		$ru_link = UserLink(getDataPrefix($revision, "ru_"));
		$revdetail = " ".format(__("by {0} on {1}"), $ru_link, formatdate($revision['revdate']));
	}
	else
		$revdetail = '';
	$reply .= $revdetail;
	$reply .= "<br>";
}

$hideTricks = " <a href=\"javascript:void(0)\" onclick=\"showRevision(".$id.",".$post["currentrevision"]."); hideTricks(".$id.")\">".__("Back")."</a>";
$reply .= $hideTricks;

echo $reply;

