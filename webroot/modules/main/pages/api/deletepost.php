<?php
//page /api/deletepost

function request($pid=0, $del=0, $reason='')
{
	$post = Fetch::post($pid);
	$tid = $post['thread'];
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);
	
	Permissions::assertCanViewForum($forum);
	Permissions::assertCanDeletePost($post, $thread, $forum);
	
	$del = (int)$del;

	if($del == 1)
		Sql::query('UPDATE {posts} SET deleted=1,deletedby=?,reason=? WHERE id=?',
			Session::id(), $reason, $pid);
	else
		Sql::query('UPDATE {posts} SET deleted=0 WHERE id=?',
			$pid);

	json(Url::format('/post/#', $pid));
}