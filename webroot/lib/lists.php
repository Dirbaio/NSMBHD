<?php

function forumAccessControlSql()
{
	global $loguser;
	$realpl = $loguser["powerlevel"];
	if($realpl < 0) $realpl = 0;
	return "f.minpower <= ".$realpl;
}

function listThreads($threads, $dostickies = true, $showforum = false)
{
	global $haveStickies, $mobileLayout;
	
	$forumList = "";
	$haveStickies = 0;
	$cellClass = 0;

	while($thread = Fetch($threads))
	{
		$forumList .= listThread($thread, $cellClass, $dostickies, $showforum);
		$cellClass = ($cellClass + 1) % 2;
	}

	if($showforum)
		$forum = "<th style=\"width: 25%;\">".__("Forum")."</th>";
	else
		$forum = "";
		
	if($mobileLayout)
		return "
		<table class=\"outline margin width100\">
			<tr class=\"header1\">
				<th>".__("Thread")."</th>
				<th style=\"min-width:150px;width:15%;\">".__("Last post")."</th>
			</tr>
			$forumList
		</table>";
	else
		return "
		<table class=\"outline margin width100\">
			<tr class=\"header1\">
				<th style=\"width: 20px;\">&nbsp;</th>
				<th style=\"width: 16px;\">&nbsp;</th>
				<th style=\"width: 35%;\">".__("Title")."</th>
				$forum
				<th>".__("Started by")."</th>
				<th>".__("Replies")."</th>
				<th>".__("Views")."</th>
				<th style=\"min-width:150px\">".__("Last post")."</th>
			</tr>
			$forumList
		</table>";
}

function doThreadPreview($tid)
{
	global $mobileLayout;
	if($mobileLayout) return;
	
	$rPosts = Query("
		select
			{posts}.id, {posts}.date, {posts}.num, {posts}.deleted, {posts}.options, {posts}.mood, {posts}.ip,
			{posts_text}.text, {posts_text}.text, {posts_text}.revision,
			u.(_userfields)
		from {posts}
		left join {posts_text} on {posts_text}.pid = {posts}.id and {posts_text}.revision = {posts}.currentrevision
		left join {users} u on u.id = {posts}.user
		where thread={0} and deleted=0
		order by date desc limit 0, 20", $tid);

	if(NumRows($rPosts))
	{
		$posts = "";
		while($post = Fetch($rPosts))
		{
			$cellClass = ($cellClass+1) % 2;

			$poster = getDataPrefix($post, "u_");

			$nosm = $post['options'] & 2;
			$nobr = $post['options'] & 4;

			$posts .= Format(
	"
			<tr>
				<td class=\"cell2\" style=\"width: 15%; vertical-align: top;\">
					{1}
				</td>
				<td class=\"cell{0}\">
					<button style=\"float: right;\" onclick=\"insertQuote({2});\">".__("Quote")."</button>
					<button style=\"float: right;\" onclick=\"insertChanLink({2});\">".__("Link")."</button>
					{3}
				</td>
			</tr>
	",	$cellClass, UserLink($poster), $post['id'], CleanUpPost($post['text'], $poster['name'], $nosm));
		}
		Write(
	"
		<table class=\"outline margin\">
			<tr class=\"header0\">
				<th colspan=\"2\">".__("Thread review")."</th>
			</tr>
			{0}
		</table>
	",	$posts);
	}
}
