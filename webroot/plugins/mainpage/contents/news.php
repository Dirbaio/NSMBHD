<?php
$pl = $loguser['powerlevel'];
if($pl < 0) $pl = 0;

$rFora = Query("select * from {forums} where id = {0}", Settings::pluginGet("forum"));

if(NumRows($rFora))
{
	$forum = Fetch($rFora);
	if($forum['minpower'] > $pl)
		Kill(__("You are not allowed to browse this forum."));
} else
	Kill(__("Unknown forum ID."));

$fid = $forum['id'];

$total = $forum['numthreads'];

if(isset($_GET['from']))
	$from = (int)$_GET['from'];
else
	$from = 0;

$tpp = 5;

print "<h2 style='text-align:center;'>Latest News</h2>";
$rThreads = Query("	SELECT 
						t.id, t.title, t.closed, t.replies, t.lastpostid,
						p.date, p.options, 
						pt.text, 
						su.(_userfields),
						lu.(_userfields)
					FROM 
						{threads} t
						LEFT JOIN {posts} p ON p.thread=t.id AND p.date=(SELECT MIN(p2.date) FROM {posts} p2 WHERE p2.thread=t.id)
						LEFT JOIN {posts_text} pt ON pt.pid = p.id AND pt.revision = p.currentrevision 
						LEFT JOIN {users} su ON su.id=t.user
						LEFT JOIN {users} lu ON lu.id=t.lastposter
					WHERE forum={0}
					ORDER BY sticky DESC, lastpostdate DESC LIMIT {1}, {2}", 
						$fid, 0, Settings::pluginGet("newcount"));

$haveStickies = 0;

while($thread = Fetch($rThreads))
{
	$starter = getDataPrefix($thread, "su_");
	$last = getDataPrefix($thread, "lu_");
	$tags = ParseThreadTags($thread['title']);

	$lastLink = "";
	if($thread['lastpostid'])
		$lastLink = " ".actionLinkTag("&raquo;", "thread", "", "pid=".$thread['lastpostid']."#".$thread['lastpostid']);
		
	if($thread['replies'] == 0) $lastLink = "";
	
	$subtitle = strip_tags($thread['subtitle']);
	if($subtitle != "") $subtitle = '<br />'.$subtitle;
	
	$postdate = formatdate($thread['date']);
	$posttext = CleanUpPost($thread['text'],$thread['u_name'], false, false);

	$comments = Plural($thread['replies'], "comment");
	$comments = actionLinkTag($comments, "thread", $thread['id']).". ";

	if($thread['replies'] != 0)
		$comments .="Last comment by ".UserLink($last).". $lastLink";

	$newreply = actionLinkTag("Post a comment", "newreply", $thread['id']);

	
	if($thread['sticky'])
	{
		$forumList .= "<table class='outline margin width100'>";
		$forumList .= "<tr class='cell1'><td style='border: 1px solid #000; padding:16px' colspan='2'>$posttext</td></tr>";
		$forumList .="</table>";
	}
	else
	{
		$forumList .= "<table class='outline margin width100'>";
		$forumList .= "
		<tr class=\"header1\" >
			<th style='text-align:left;'><span style='font-size:15px'>".$tags[0]."</span><span style='font-weight:normal;'>$subtitle</span></th>
			<th style='text-align:left; width:150px; font-weight:normal;'>Posted by ".UserLink($starter)."<br />$postdate</th>
		</tr>";
		$forumList .= "<tr class='cell1'><td colspan='2' style='padding:10px'>$posttext</td></tr>";
		$forumList .= "<tr class='cell0'><td>$comments</td><td style=\"border-left: 0px none;\">$newreply</td></tr>";
		$forumList .="</table>";
	}
}

Write($forumList);

