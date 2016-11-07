<?php
//page /api/newprivate

function request($recipients, $title='', $text='')
{
	Permissions::assertCanDoStuff();
	
	$recipients[] = Session::id();
	$recipients = array_unique($recipients);

	$recipientUsers = array();
	foreach ($recipients as $uid) {
		$recipientUsers[] = Fetch::user($uid);
	}
	
	$title = trim($title);

	Validate::notEmpty($text, __('Your post is empty. Enter a message and try again.'));
	Validate::notEmpty($title, __('Your thread is unnamed. Enter a thread title and try again.'));

	$now = time();

	// Create the thread
	Sql::query('INSERT INTO {pmthreads} (user, title, lastpostdate, lastpostuser) VALUES (?,?,?,?)',
		Session::id(), $title, $now, Session::id());

	$tid = Sql::insertId();

	// Create the first post
	Sql::query('INSERT INTO {pmsgs} (thread, user, date, ip) VALUES (?,?,?,?)',
		$tid, Session::id(), $now, $_SERVER['REMOTE_ADDR']);

	$pid = Sql::insertId();
	Sql::query('UPDATE {pmthreads} SET lastpostid=? where id=?', $pid, $tid);

	Sql::query('INSERT INTO {pmsgs_text} (pid,text,revision,user,date) VALUES (?,?,?,?,?)', 
		$pid, $text, 0, Session::id(), $now);
	
	// Add the people to the thread
	foreach($recipientUsers as $u)
		Sql::query('INSERT INTO {pmthread_members} (thread, user) VALUES (?, ?)', $tid, $u['id']);
		
	//Erase the draft
	Sql::query('DELETE FROM {drafts} WHERE user=? AND type=? AND target=?', Session::id(), 3, 0);

	json(Url::format('/u/#-:/pm/#-:', Session::id(), Session::get('name'), $tid, $title));
}