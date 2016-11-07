<?php 
//page /#id
//page /#id-:
//page /#id/p#from
//page /#id-:/p#from

//ABXD LEGACY
//page /forum/#id
//page /forum/#id-:
//page /forum.php

function request($id, $from=0)
{
	$fid = $id;
	$forum = Fetch::forum($fid);

	Permissions::assertCanViewForum($forum);

	if($from == 0)
		Url::setCanonicalUrl('/#-:', $forum['id'], $forum['title']);
	else
		Url::setCanonicalUrl('/#-:/p#', $forum['id'], $forum['title'], $from);

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
				lu.(_userfields)
			FROM
				{threads} t
				LEFT JOIN {threadsread} tr ON tr.thread=t.id AND tr.id=?
				LEFT JOIN {users} su ON su.id=t.user
				LEFT JOIN {users} lu ON lu.id=t.lastpostuser
			WHERE forum=?
			ORDER BY sticky DESC, lastpostdate DESC 
			LIMIT ?, ?', 
			Session::id(), $fid, $from, $tpp);
	else
		$threads = Sql::queryAll(
			'SELECT
				t.*,
				0 as numnew,
				su.(_userfields),
				lu.(_userfields)
			FROM
				{threads} t
				LEFT JOIN {users} su ON su.id=t.user
				LEFT JOIN {users} lu ON lu.id=t.lastpostuser
			WHERE forum=?
			ORDER BY sticky DESC, lastpostdate DESC 
			LIMIT ?, ?', 
			$fid, $from, $tpp);


	// SUBFORUMS


	if(Session::isLoggedIn())
		$forums = Sql::queryAll(
			'SELECT 
				f.*,
				lu.(_userfields),
				(
					SELECT COUNT(*)
					FROM {threads} t
					LEFT JOIN {threadsread} tr ON tr.thread=t.id AND tr.id=?
					WHERE t.forum=f.id AND t.lastpostdate > IFNULL(tr.date, 0)
				) numnew
			FROM {forums} f
			LEFT JOIN {users} lu ON lu.id = f.lastpostuser
			ORDER BY forder',
			Session::id());
	else
		$forums = Sql::queryAll(
			'SELECT 
				f.*,
				lu.(_userfields),
				0 as numnew
			FROM {forums} f
			LEFT JOIN {users} lu ON lu.id = f.lastpostuser
			ORDER BY forder');

	$subforums = array();
	foreach($forums as $subforum)
	{
		if(!Permissions::canViewForum($subforum)) continue;
		if($subforum['catid'] == -$forum['id'])
			$subforums[] = $subforum;
	}

	$categories = array(
		array(
			'name' => __('Subforums'),
			'forums' => $subforums
		)
	);

	$breadcrumbs = array(
		array('url' => Url::format('/#-:', $forum['id'], $forum['title']), 'title' => $forum['title'])
	);

	$actionlinks = array(
	);

	if(Permissions::canCreateThread($forum))
		$actionlinks[] = array('url' => Url::format('/#-:/newthread', $forum['id'], $forum['title']), 'title' => __('Post thread'));
	if(Session::isLoggedIn())
		$actionlinks[] = array('title' => __('Mark as read'), 'ng' => 'doAction("/api/markasread", {fid: '.$fid.'})');

	renderPage('forum.html', array(
		'forum' => $forum, 
		'threads' => $threads, 
		'categories' => $categories, 
		'hotcount' => 30, 
		'paging' => array(
			'perpage' => $tpp,
			'from' => $from,
			'total' => $forum['numthreads'],
			'base' => Url::format('/#-:', $forum['id'], $forum['title']),
		),
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => $forum['title'],
	));
}

