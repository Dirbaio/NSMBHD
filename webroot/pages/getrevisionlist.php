<?php

$ajaxPage = true;

$id = (int)$_GET["id"];

$qPost = "select currentrevision, thread from {posts} where id={0}";
$rPost = Query($qPost, $id);
if(NumRows($rPost))
	$post = Fetch($rPost);
else
	die(format(__("Unknown post ID #{0}."), $id)." ".$hideTricks);

$qThread = "select forum from {threads} where id={0}";
$rThread = Query($qThread, $post['thread']);
$thread = Fetch($rThread);
$qForum = "select minpower from {forums} where id={0}";
$rForum = Query($qForum, $thread['forum']);
$forum = Fetch($rForum);
if($forum['minpower'] > $loguser['powerlevel'])
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

