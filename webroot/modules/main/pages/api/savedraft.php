<?php
//page /api/savedraft

function request($type, $target, $data)
{
	Session::checkLoggedIn();

	$type = (int)$type;
	$target = (int)$target;
	$data = json_encode($data);

	if($type == 0) { // New post draft
		$tid = $target;
		$thread = Fetch::thread($tid);
		$fid = $thread['forum'];
	}
	else if($type == 1) { // New thread draft
		$fid = $target;
	}
	else if($type == 2) { // Post edit draft
		$pid = $target;
		$post = Fetch::post($pid);
		$tid = $post['thread'];
		$thread = Fetch::thread($tid);
		$fid = $thread['forum'];
	}
	else {
		$fid = 0;
	}
	
	if($fid != 0) {
		$forum = Fetch::forum($fid);
		Permissions::assertCanViewForum($forum);
	}
	
	Sql::query('INSERT INTO {drafts} (user, type, target, date, data) VALUES (?,?,?,?,?)
				ON DUPLICATE KEY UPDATE date=?, data=?',
		Session::id(), $type, $target, time(), $data, time(), $data);
	
	json('ok');
}