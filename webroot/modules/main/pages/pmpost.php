<?php 
//page /pm/post/#pid

function request($pid)
{
	$post = Sql::querySingle('SELECT * FROM {pmsgs} where id=?', $pid);
	if(![pst])
		fail(__('Unknown message ID.'));
	
	$tid = $post['thread'];

	if(Permissions::canSnoopMessages()) {
		$thread = Sql::querySingle('SELECT t.* 
			FROM {pmthreads} t
			WHERE id=?', 
			$tid);
	} else {
		$thread = Sql::querySingle('SELECT t.* 
			FROM {pmthreads} t
			JOIN {pmthread_members} m on m.thread=t.id
			WHERE id=? and m.user=?', 
			$tid, Session::id());
	}
	if(!$thread)
		fail(__('Unknown message ID.'));

	$ppp = 20;

	$count = Sql::queryValue("SELECT COUNT(*) FROM {pmsgs} WHERE thread=? AND date<=? AND id!=?", 
								$tid, $post['date'], $pid);

	$from = (floor($count / $ppp)) * $ppp;

	if($from == 0)
		$url = Url::format('/u/#-:/pm/#-:', Session::id(), Session::get('name'), $thread['id'], $thread['title']);
	else
		$url = Url::format('/u/#-:/pm/#-:/p#', Session::id(), Session::get('name'), $thread['id'], $thread['title'], $from);

	$url .= '#'.$pid;

	Url::redirect($url);
}

