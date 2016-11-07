<?php 
//page /u/#id/posts
//page /u/#id-:/posts
//page /u/#id/posts/p#from
//page /u/#id-:/posts/p#from

//ABXD LEGACY
//page /listposts/#id
//page /listposts/#id-:
//page /listposts.php

function request($id, $from=0)
{
	$user = Fetch::user($id);

	if($from)
		Url::setCanonicalUrl('/u/#-:/posts/p#', $user['id'], $user['name'], $from);
	else
		Url::setCanonicalUrl('/u/#-:/posts', $user['id'], $user['name']);

	$ppp = 20;

	$posts = Sql::queryAll(
		'SELECT
			p.*,
			pt.text, pt.revision, pt.user AS revuser, pt.date AS revdate,
			userposted.(_userfields,rankset,title,picture,posts,postheader,signature,signsep,lastposttime,lastactivity,regdate,globalblock),
			t.(id, title),
			f.(id, title, minpower),
			useredited.(_userfields),
			userdeleted.(_userfields)
		FROM
			{posts} p
			LEFT JOIN {posts_text} pt ON pt.pid = p.id AND pt.revision = p.currentrevision
			LEFT JOIN {users} userposted ON userposted.id = p.user
			LEFT JOIN {users} useredited ON useredited.id = pt.user
			LEFT JOIN {users} userdeleted ON userdeleted.id = p.deletedby
			LEFT JOIN {threads} t ON t.id = p.thread
			LEFT JOIN {forums} f ON f.id = t.forum
		WHERE p.user=?
		ORDER BY date ASC LIMIT ?, ?', $user['id'], $from, $ppp);

	foreach($posts as &$post)
		if(!Permissions::canViewForum($post['f']))
			$post['restricted'] = true;

	$breadcrumbs = array(
		array('url' => Url::format('/members'), 'title' => __("Members")),
		array('user' => $user),
		array('url' => Url::format('/u/#-:/posts', $user['id'], $user['name']), 'title' => __('Posts'), 'weak' => true),
	);

	$actionlinks = array();

	renderPage('memberposts.html', array(
		'user' => $user,
		'posts' => $posts,
		'showThread' => true,
		'paging' => array(
			'perpage' => $ppp,
			'from' => $from,
			'total' => $user['posts'],
			'base' => Url::format('/u/#-:/posts', $user['id'], $user['name']),
		),
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => $forum['title'],
	));
}