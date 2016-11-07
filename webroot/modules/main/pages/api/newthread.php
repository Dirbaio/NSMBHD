<?php
//page /api/newthread

function request($fid, $title='', $text='', $poll=false, $pollquestion='', $polldoublevote=false, $pollchoices=NULL)
{
	$forum = Fetch::forum($fid);

	Permissions::assertCanViewForum($forum);
	Permissions::assertCanCreateThread($forum);

	$title = trim($title);

	Validate::notEmpty($text, __('Your post is empty. Enter a message and try again.'));
	Validate::notEmpty($title, __('Your thread is unnamed. Enter a thread title and try again.'));

	$lastPost = time() - Session::get('lastposttime');
	if($lastPost < 10)//Settings::get('floodProtectionInterval'))
	{
		//Check for last post the user posted.
		$lastPost = Sql::querySingle('SELECT * FROM {posts} WHERE user=? ORDER BY date DESC LIMIT 1', Session::id());

		//If it looks similar to this one, assume the user has double-clicked the button.
		//if($lastPost['thread'] == $tid)
		//	json(Url::format('/post/#', $lastPost['id']));

		fail(__('You\'re going too damn fast! Slow down a little.'));
	}

	if($poll) {
		Validate::notEmpty($pollquestion, __('You need to enter a poll question to make a poll.'));
		if(count($pollchoices) < 2)
			fail(__('You need to enter at least two options to make a poll.'));
		foreach($pollchoices as $choice)
			Validate::notEmpty($choice['text'], __('You have blank options in your poll.'));
	}

	$now = time();

	// Create the poll if needed.
	if($poll) 
	{
		$polldoublevote = $polldoublevote ? 1 : 0;

		Sql::query(
			'INSERT INTO {poll} (question, doublevote) VALUES (?, ?)',
			$pollquestion, $polldoublevote);

		$pollid = Sql::insertId();

		foreach($pollchoices as $choice) {
			Validate::color($choice['color']);
			Sql::query(
				'INSERT INTO {poll_choices} (poll, choice, color) VALUES (?, ?, ?)',
				$pollid, $choice['text'], $choice['color']);
		}
	}
	else
		$pollid = 0;

	// Create the thread
	Sql::query('INSERT INTO {threads} (forum, user, title, lastpostdate, lastpostuser, poll) VALUES (?,?,?,?,?,?)',
		$fid, Session::id(), $title, $now, Session::id(), $pollid);

	$tid = Sql::insertId();

	// Create the first post
	Sql::query('INSERT INTO {posts} (thread, user, date, editdate, ip, num) VALUES (?,?,?,?,?,?)',
		$tid, Session::id(), $now, $now, $_SERVER['REMOTE_ADDR'], Session::get('posts')+1);

	$pid = Sql::insertId();
	Sql::query('UPDATE {threads} SET lastpostid=? where id=?', $pid, $tid);

	Sql::Query('INSERT INTO {posts_text} (pid,text,revision,user,date) VALUES (?,?,?,?,?)', 
		$pid, $text, 0, Session::id(), $now);

	//Update counters
	Sql::query('UPDATE {threads} SET firstpostid=?, lastpostid=?, date=? where id=?',
		$pid, $pid, $now, $tid);

	Sql::query('UPDATE {forums} SET numposts=numposts+1, numthreads=numthreads+1, lastpostdate=?, lastpostuser=?, lastpostid=? where id=?',
		$now, Session::id(), $pid, $fid);

	Sql::query('UPDATE {users} SET posts=posts+1, lastposttime=? WHERE id=?',
		time(), Session::id());

	//Erase the draft
	Sql::query('DELETE FROM {drafts} WHERE user=? AND type=? AND target=?', Session::id(), 1, $fid);

	json(Url::format('/#-#/#-#', $forum['id'], $forum['title'], $tid, $title));
}