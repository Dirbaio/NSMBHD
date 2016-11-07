<?php 
//page /u/#id
//page /u/#id-:
//page /u/#id/p#from
//page /u/#id-:/p#from

//ABXD LEGACY
//page /profile/#id
//page /profile/#id-:
//page /profile.php

function getPowerlevelName($pl) {
	$powerlevels = array(
		-1 => __("Banned"),
		0 => __("Normal"),
		1 => __("Local mod"),
		2 => __("Full mod"),
		3 => __("Admin"),
		4 => __("Root"),
		5 => __("System")
	);
	return $powerlevels[$pl];
}

function getSexName($sex) {
	$sexes = array(
		0 => __("Male"),
		1 => __("Female"),
		2 => __("N/A"),
	);

	return $sexes[$sex];
}

function request($id, $from=0)
{
	$user = Fetch::user($id);

	if($from == 0)
		Url::setCanonicalUrl('/u/#-:', $user['id'], $user['name']);
	else
		Url::setCanonicalUrl('/u/#-:/p#', $user['id'], $user['name'], $from);

	$breadcrumbs = array(
		array('url' => Url::format('/members'), 'title' => __("Members")),
		array('user' => $user),
	);

	$actionlinks = array();

	if(Permissions::canEditUser($user))
		$actionlinks[] = array('url' => Url::format('/u/#-:/edit', $user['id'], $user['name']), 'title' => __('Edit profile'));
	if($user['id'] == Session::id() || Permissions::canSnoopMessages())
		$actionlinks[] = array('url' => Url::format('/u/#-:/pm', $user['id'], $user['name']), 'title' => __('Messages'));

	$actionlinks[] = array('url' => Url::format('/u/#-:/threads', $user['id'], $user['name']), 'title' => __('Threads'));
	$actionlinks[] = array('url' => Url::format('/u/#-:/posts', $user['id'], $user['name']), 'title' => __('Posts'));

	$user['powerlevelname'] = getPowerlevelName($user['powerlevel']);
	$user['sexname'] = getSexName($user['sex']);

	if($user['lastpostid']) {
		$lastPost = Fetch::post($user['lastpostid']);
		$lastPostThread = Fetch::thread($lastPost['thread']);
		$lastPostForum = Fetch::forum($lastPostThread['forum']);
		if(Permissions::canViewForum($lastPostForum)) {
			$user['lastpostthread'] = $lastPostThread['id'];
			$user['lastpostthreadtitle'] = $lastPostThread['title'];
			$user['lastpostforum'] = $lastPostForum['id'];
			$user['lastpostforumtitle'] = $lastPostForum['title'];
		} else 
			$user['lastpostrestricted'] = True;
	}
	
	$samplePostText = 'This is a sample post. You [b]probably[/b] [i]already[/i] [u]know[/u] what this is for.
[spoiler=Spoiler preview]
Spoiler Test

[quote=Luigi]
"I\'m a-Luigi, number one!"
[/quote]

[/spoiler] 
[quote=Goomba][quote=Mario]Woohoo! [url=http://www.mariowiki.com/Super_Mushroom]That\'s what I needed[/url]![/quote]Oh, nooo! *stomp*[/quote]

Well, what more could you [url=http://en.wikipedia.org]want to know[/url]? Perhaps how to do the classic infinite loop?
[source=c]while(true){
    printf("Hello World!");
}[/source]';

	$samplePost = array(
		'userposted' => $user,
		'text' => $samplePostText,
		'sample' => true,
		'links' => array(
			array('title' => __('Link'), 'js' => 'return false;'),
			array('title' => __('Another link'), 'js' => 'return false;'),
		),
	);

	$ppp = 20;

	$comments = Sql::queryAll("SELECT
		u.(_userfields),
		c.id, c.cid, c.text, c.date
		FROM {usercomments} c
		LEFT JOIN {users} u ON u.id = c.cid
		WHERE c.uid=?
		ORDER BY c.date DESC
		LIMIT ?, ?", 
		$user['id'], $from, $ppp);
		
	$totalcomments = Sql::queryValue('SELECT count(*) from {usercomments} where uid=?', $user['id']);

	renderPage('member.html', array(
		'user' => $user,
		'post' => $samplePost,
		'comments' => $comments,
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => $forum['title'],
		'commentspaging' => array(
			'perpage' => $ppp,
			'from' => $from,
			'total' => $totalcomments, //+1 for the OP
			'base' => Url::format('/u/#-:', $user['id'], $user['name']),
		),
	));
}