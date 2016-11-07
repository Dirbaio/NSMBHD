<?php
//page /api/usercomment

function request($text='', $id=0)
{
	Permissions::assertCanDoStuff();

	$user = Fetch::user($id);

	if(!$text)
		fail(__("Your post is empty. Enter a message and try again."));

	$now = time();

	Sql::query("insert into {usercomments} (uid, cid, date, text) values (?,?,?,?)", $user['id'], Session::get('id'), time(), $text);

	json('ok');
}