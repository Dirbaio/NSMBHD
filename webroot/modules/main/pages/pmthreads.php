<?php 
//page /u/#id/pm
//page /u/#id-:/pm
//page /u/#id/pm/p#from
//page /u/#id-:/pm/p#from

function request($id, $from=0)
{
	$user = Fetch::user($id);

	if($user['id'] != Session::id())
		Permissions::assertCanSnoopMessages();
	
	if($from)
		Url::setCanonicalUrl('/u/#-:/pm/p#', $user['id'], $user['name'], $from);
	else
		Url::setCanonicalUrl('/u/#-:/pm', $user['id'], $user['name']);

	$tpp = 50;

	$threads = Sql::queryAll(
		'SELECT
			t.*,
			(
				SELECT COUNT(*)
				FROM {pmsgs} p
				WHERE p.thread=t.id AND p.date > tr.readdate
			) numnew,
			(
				SELECT MIN(p.id)
				FROM {pmsgs} p
				WHERE p.thread=t.id AND p.date > tr.readdate
			) idnew,
			su.(_userfields),
			lu.(_userfields)
		FROM
			{pmthreads} t
			JOIN {pmthread_members} tr ON tr.thread=t.id AND tr.user=?
			LEFT JOIN {users} su ON su.id=t.user
			LEFT JOIN {users} lu ON lu.id=t.lastpostuser
		ORDER BY lastpostdate DESC 
		LIMIT ?, ?', 
		$user['id'], $from, $tpp);

	$total = Sql::queryValue('SELECT COUNT(*) FROM {pmthreads} t JOIN {pmthread_members} m on t.id=m.thread WHERE m.user=?', $user['id']);
	$breadcrumbs = array(
		array('url' => Url::format('/members'), 'title' => __("Members")),
		array('user' => $user),
		array('url' => Url::format('/u/#-:/pm', $user['id'], $user['name']), 'title' => __('Messages'), 'weak' => true),
	);

	$actionlinks = array();

	if($user['id'] == Session::id())
		$actionlinks[] = array('url' => Url::format('/u/#-:/pm/new', $user['id'], $user['name']), 'title' => __('New message'));

	renderPage('pmthreads.html', array(
		'user' => $user,
		'threads' => $threads,
		'hotcount' => 30, 
		'paging' => array(
			'perpage' => $tpp,
			'from' => $from,
			'total' => $total,
			'base' => Url::format('/u/#-:/pm', $user['id'], $user['name']),
		),
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => 'Messages',
	));
}