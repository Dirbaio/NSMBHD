<?php
//page /api/stickthread

function request($tid)
{
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);
	
	Permissions::assertCanViewForum($forum);
	Permissions::assertCanMod($forum);

	if($thread['sticky'])
		fail(__('This thread is already stickied.'));
	
	Sql::query('UPDATE {threads} SET sticky=1 WHERE id=?', $tid);

	json(Url::format('/#-:/#-:', $fid, $forum['title'], $tid, $thread['title']));
}