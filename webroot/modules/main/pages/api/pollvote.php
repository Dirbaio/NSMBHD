<?php
//page /api/pollvote

function request($tid, $choice)
{
	$thread = Fetch::thread($tid);
	$forum = Fetch::forum($thread['forum']);
	Permissions::assertCanReply($thread, $forum);

	$loguserid = Session::id();
	$doublevote = Sql::queryValue(
		'SELECT doublevote FROM {poll} WHERE id=?',
		$thread['poll']);

	$existing = Sql::queryValue(
		'SELECT count(*) FROM {pollvotes} WHERE poll=? AND choiceid=? AND user=?',
		$thread['poll'], $choice, $loguserid);

	$ok = Sql::queryValue(
		'SELECT count(*) FROM {poll_choices} WHERE poll=? AND id=?',
		$thread['poll'], $choice);

	if(!$ok)
		fail(__('Trying to vote for a choice from another poll!'));

	if($doublevote)
	{
		//Multivote.
		if ($existing)
			Sql::query('DELETE FROM {pollvotes} WHERE poll=? AND choiceid=? AND user=?',
				$thread['poll'], $choice, $loguserid);
		else
			Sql::query('INSERT INTO {pollvotes} (poll, choiceid, user) VALUES (?, ?, ?)',
				$thread['poll'], $choice, $loguserid);
	}
	else
	{
		//Single vote only?
		//Remove any old votes by this user on this poll, then add a new one.
		Sql::query('DELETE FROM {pollvotes} WHERE poll=? AND user=?',
			$thread['poll'], $loguserid);
		if(!$existing)
			Sql::query('INSERT INTO {pollvotes} (poll, choiceid, user) VALUES (?, ?, ?)',
				$thread['poll'], $choice, $loguserid);
	}

	json(Fetch::pollComplete($thread['poll']));
}