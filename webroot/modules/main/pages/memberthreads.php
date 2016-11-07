<?php 
//page /u/#id/threads
//page /u/#id-:/threads
//page /u/#id/threads/p#from
//page /u/#id-:/threads/p#from

//ABXD LEGACY
//page /listthreads/#id
//page /listthreads/#id-:
//page /listthreads.php

function request($id, $from=0)
{
	$user = Fetch::user($id);

	if($from)
		Url::setCanonicalUrl('/u/#-:/threads/p#', $user['id'], $user['name'], $from);
	else
		Url::setCanonicalUrl('/u/#-:/threads', $user['id'], $user['name']);


	$tpp = 50;

	if(Session::id())
		$threads = Sql::queryAll(
			'SELECT
				t.*,
				(
					SELECT COUNT(*)
					FROM {posts} p
					WHERE p.thread=t.id AND IF(p.id = t.lastpostid, p.editdate, p.date) > IFNULL(tr.date, 0)
				) numnew,
				(
					SELECT MIN(p.id)
					FROM {posts} p
					WHERE p.thread=t.id AND IF(p.id = t.lastpostid, p.editdate, p.date) > IFNULL(tr.date, 0)
				) idnew,
				su.(_userfields),
				lu.(_userfields),
				f.(id, title)
			FROM
				{threads} t
				LEFT JOIN {threadsread} tr ON tr.thread=t.id AND tr.id=?
				LEFT JOIN {users} su ON su.id=t.user
				LEFT JOIN {users} lu ON lu.id=t.lastpostuser
				LEFT JOIN {forums} f ON f.id=t.forum
			WHERE user=?
			ORDER BY date ASC 
			LIMIT ?, ?', 
			Session::id(), $user['id'], $from, $tpp);
	else
		$threads = Sql::queryAll(
			'SELECT
				t.*,
				0 as numnew,
				su.(_userfields),
				lu.(_userfields),
				f.(id, title, minpower)
			FROM
				{threads} t
				LEFT JOIN {users} su ON su.id=t.user
				LEFT JOIN {users} lu ON lu.id=t.lastpostuser
				LEFT JOIN {forums} f ON f.id=t.forum
			WHERE user=?
			ORDER BY date ASC 
			LIMIT ?, ?', 
			$user['id'], $from, $tpp);

	// Permissions
	foreach($threads as &$thread)
		if(!Permissions::canViewForum($thread['f']))
			$thread['restricted'] = true;
	unset($thread);

	$total = Sql::queryValue('SELECT COUNT(*) FROM {threads} WHERE user=?', $user['id']);

	$breadcrumbs = array(
		array('url' => Url::format('/members'), 'title' => __("Members")),
		array('user' => $user),
		array('url' => Url::format('/u/#-:/threads', $user['id'], $user['name']), 'title' => __('Threads'), 'weak' => true),
	);

	$actionlinks = array();

	renderPage('components/threadList.html', array(
		'user' => $user,
		'threads' => $threads,
		'showForum' => true,
		'paging' => array(
			'perpage' => $tpp,
			'from' => $from,
			'total' => $total,
			'base' => Url::format('/u/#-:/threads', $user['id'], $user['name']),
		),
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => $forum['title'],
	));
}