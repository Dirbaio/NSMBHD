<?php 
//page /post/#pid

//page /post.php

function request($pid)
{
	$post = Fetch::post($pid);
	$tid = $post['thread'];
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);

	Permissions::assertCanViewForum($forum);

	$ppp = 20;

	$count = Sql::queryValue("SELECT COUNT(*) FROM {posts} WHERE thread=? AND date<=? AND id!=?", 
								$tid, $post['date'], $pid);

	$from = (floor($count / $ppp)) * $ppp;

	if($from == 0)
		$url = Url::format('/#-:/#-:', $forum['id'], $forum['title'], $thread['id'], $thread['title']);
	else
		$url = Url::format('/#-:/#-:/p#', $forum['id'], $forum['title'], $thread['id'], $thread['title'], $from);

	$url .= '#'.$pid;

	Url::redirect($url);
}

