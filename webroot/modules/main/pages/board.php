<?php 
//page /

//ABXD LEGACY
//page /board
//page /board.php

function request()
{
	Url::setCanonicalUrl('/');

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

	$categories = Sql::queryAll('SELECT * FROM {categories} ORDER BY corder');

	foreach($categories as &$cat)
	{
		$cat['forums'] = array();
		foreach($forums as $forum)
			if($forum['catid'] == $cat['id'])
			{
				if(!Permissions::canViewForum($forum)) continue;
				$cat['forums'][] = $forum;
				foreach($forums as $subforum)
				{
					if(!Permissions::canViewForum($subforum)) continue;
					if($subforum['catid'] == -$forum['id'])
						$cat['forums'][] = $subforum;
				}
			}
	}

	$breadcrumbs = array(
	);

	$actionlinks = array(
	);

	if(Session::isLoggedIn())
		$actionlinks[] = array('title' => __('Mark all as read'), 'ng' => 'doAction("/api/markasread", {fid: 0})');

	renderPage('components/forumList.html', array(
		'categories' => $categories,
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => '',
	));
}

