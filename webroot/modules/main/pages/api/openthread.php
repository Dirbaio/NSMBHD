<?php
//page /api/openthread

function request($tid)
{
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);
	
	Permissions::assertCanViewForum($forum);
	Permissions::assertCanMod($forum);

	if(!$thread['closed'])
		fail(__('This thread is not closed.'));
	
	Sql::query('UPDATE {threads} SET closed=0 WHERE id=?', $tid);

	json(Url::format('/#-:/#-:', $fid, $forum['title'], $tid, $thread['title']));
}