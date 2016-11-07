<?php
//page /api/renamethread

function request($tid, $name)
{
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);
	
	Permissions::assertCanViewForum($forum);
	Permissions::assertCanEditThread($thread, $forum);

	Validate::notEmpty($name, __('Your thread is unnamed. Enter a thread title and try again.'));

	Sql::query('UPDATE {threads} SET title=? WHERE id=?', $name, $tid);
	$thread['title'] = $name;
	
	json(Url::format('/#-:/#-:', $fid, $forum['title'], $tid, $thread['title']));
}