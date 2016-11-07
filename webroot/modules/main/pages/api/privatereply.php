<?php
//page /api/privatereply

function request($text='', $tid=0)
{
	$thread = Sql::querySingle('SELECT t.* 
		FROM {pmthreads} t
		JOIN {pmthread_members} m on m.thread=t.id
		WHERE id=? and m.user=?', 
		$tid, Session::id());
	if(!$thread)
		fail(__('Unknown message ID.'));

	Validate::notEmpty($text, __('Your post is empty. Enter a message and try again.'));

	$now = time();

	Sql::query("INSERT INTO {pmsgs} (thread, user, date, ip) VALUES (?,?,?,?)",
		$tid, Session::id(), $now, $_SERVER['REMOTE_ADDR']);

	$pid = Sql::insertId();

	Sql::Query("INSERT INTO {pmsgs_text} (pid,text,revision,user,date) VALUES (?,?,?,?,?)", 
		$pid, $text, 0, Session::id(), $now);

	Sql::query("UPDATE {pmthreads} SET lastpostuser=?, lastpostdate=?, replies=replies+1, lastpostid=? WHERE id=?",
		Session::id(), $now, $pid, $tid);

	//Erase the draft
	Sql::query('DELETE FROM {drafts} WHERE user=? AND type=? AND target=?', Session::id(), 4, $tid);

//	logAction('newreply', array('forum' => $fid, 'thread' => $tid, 'post' => $pid));

	json(Url::format('/u/#-:/pm/#-:', Session::id(), Session::get('name'), $thread['id'], $thread['title']));
}