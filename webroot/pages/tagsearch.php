<?php

$tag = $_GET["id"];
$tagcode = '"['.$tag.']"';
$forum = (int)$_GET["fid"];

$cond = "WHERE MATCH (t.title) AGAINST ({0} IN BOOLEAN MODE)";

if($forum)
	$cond .= " AND t.forum = {1}";

$total = Fetch(Query("SELECT count(*) from threads t $cond", $tag, $forum));
$total = $total[0];

$tpp = $loguser['threadsperpage'];
if(isset($_GET['from']))
	$from = (int)$_GET['from'];
else
	$from = 0;

if(!$tpp) $tpp = 50;
/*
$rThreads = Query("	SELECT
						t.*,
						f.(title, id),
						".($loguserid ? "tr.date readdate," : '')."
						su.(_userfields),
						lu.(_userfields)
					FROM
						{threads} t
						".($loguserid ? "LEFT JOIN {threadsread} tr ON tr.thread=t.id AND tr.id={4}" : '')."
						LEFT JOIN {users} su ON su.id=t.user
						LEFT JOIN {users} lu ON lu.id=t.lastposter
						LEFT JOIN {forums} f ON f.id=t.forum
					WHERE t.user={0} AND f.minpower <= {1}
					ORDER BY lastpostdate DESC LIMIT {2u}, {3u}", $uid, $loguser["powerlevel"], $from, $tpp, $loguserid);
*/

$rThreads = Query("	SELECT
						t.*,
						f.(title, id),
						".($loguserid ? "tr.date readdate," : '')."
						su.(_userfields),
						lu.(_userfields)
					FROM
						threads t
						".($loguserid ? "LEFT JOIN threadsread tr ON tr.thread=t.id AND tr.id={2}" : '')."
						LEFT JOIN users su ON su.id=t.user
						LEFT JOIN users lu ON lu.id=t.lastposter
						LEFT JOIN forums f ON f.id=t.forum
					$cond and f.minpower <= {3}
					ORDER BY sticky DESC, lastpostdate DESC LIMIT {4u}, {5u}",
					$tagcode, $forum, $loguserid, $loguser["powerlevel"], $from, $tpp);

$numonpage = NumRows($rThreads);

$pagelinks = PageLinks(actionLink("tagsearch", "", "tag=$tag&fid=$forum&from="), $tpp, $from, $total);

if($pagelinks)
	echo "<div class=\"smallFonts pages\">".__("Pages:")." ".$pagelinks."</div>";

if(NumRows($rThreads))
{
	$forumList = "";
	$cellClass = 0;
	$haveStickies = 0;

	while($thread = Fetch($rThreads))
	{
		$forumList .= listThread($thread, $cellClass, false, !$forum);
		$cellClass = ($cellClass + 1) % 2;
	}


	Write(
"
	<table class=\"outline margin width100\">
		<tr class=\"header1\">
			<th style=\"width: 20px;\">&nbsp;</th>
			<th style=\"width: 16px;\">&nbsp;</th>". (
			!$forum?"
			<th style=\"width: 35%;\">".__("Title")."</th>
			<th style=\"width: 25%;\">".__("Forum")."</th>":
"			<th style=\"width: 60%;\">".__("Title")."</th>"
			)."
			<th>".__("Started by")."</th>
			<th>".__("Replies")."</th>
			<th>".__("Views")."</th>
			<th style=\"min-width:150px\">".__("Last post")."</th>
		</tr>
		{0}
	</table>
",	$forumList);
} else
	Alert(format(__("Tag {0} was not found in any thread."), htmlspecialchars($tag)), __("No threads found."));

if($pagelinks)
	Write("<div class=\"smallFonts pages\">".__("Pages:")." {0}</div>", $pagelinks);


