<?php
//page /api/unstickthread

function request($tid)
{
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);
	
	Permissions::assertCanViewForum($forum);
	Permissions::assertCanMod($forum);

	if(!$thread['sticky'])
		fail(__('This thread is not stickied.'));
	
	Sql::query('UPDATE {threads} SET sticky=0 WHERE id=?', $tid);

	json(Url::format('/#-:/#-:', $fid, $forum['title'], $tid, $thread['title']));
}