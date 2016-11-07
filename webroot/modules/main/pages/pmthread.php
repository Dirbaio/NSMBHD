<?php 
//page /u/#uid-:/pm/#tid
//page /u/#uid-:/pm/#tid/p#from
//page /u/#uid-:/pm/#tid-:
//page /u/#uid-:/pm/#tid-:/p#from

function request($uid, $tid, $from=0)
{
	$user = Fetch::user($uid);
	if($uid != Session::id())
		Permissions::assertCanSnoopMessages();

	$thread = Sql::querySingle('SELECT * FROM {pmthreads} WHERE id=?', $tid);
	if(!$thread)
		fail(__('Unknown message ID.'));

	$participants = Sql::queryAll('
		SELECT u.(_userfields) 
		FROM {pmthread_members} m
		LEFT JOIN {users} u on u.id=m.user
		where m.thread=?
	', $thread['id']);

	$participants2 = array();
	foreach ($participants as $u)
		$participants2[] = $u['u'];
	$participants = $participants2;

	// Figure out if I'm in this thread
	$me = false;
	foreach ($participants as $u)
		if($u['id'] == Session::id())
			$me = true;
			
	// If I'm not, check if I should be able to snoop
	if(!$me)
		Permissions::assertCanSnoopMessages();

	// Figure out if the snooping user is in the thread, otherwise correct the url.
	$ok = false;
	foreach ($participants as $u)
		if($u['id'] == $user['id'])
			$ok = true;

	if(!$ok) {
		// If not OK, correct the user. The setCanonicalUrl below will redirect.
		$user = Fetch::user($thread['user']);
	}
	
	if($from == 0)
		Url::setCanonicalUrl('/u/#-:/pm/#-:', $user['id'], $user['name'], $thread['id'], $thread['title']);
	else
		Url::setCanonicalUrl('/u/#-:/pm/#-:/p#', $user['id'], $user['name'], $thread['id'], $thread['title'], $from);

	$ppp = 20;
	$posts = Sql::queryAll(
		'SELECT
			p.*,
			pt.text, pt.revision, pt.user AS revuser, pt.date AS revdate,
			userposted.(_userfields,rankset,title,picture,posts,postheader,signature,signsep,lastposttime,lastactivity,regdate,globalblock)
		FROM
			{pmsgs} p
			LEFT JOIN {pmsgs_text} pt ON pt.pid = p.id
			LEFT JOIN {users} userposted ON userposted.id = p.user
		WHERE thread=?
		ORDER BY date ASC LIMIT ?, ?', $tid, $from, $ppp);


	if($me) {
		// Set read date to the max date of the posts displayed in this page.
		// If the user is not viewing the last page, he will still see the unread marker.
		$readdate = 0;
		foreach($posts as $post)
			$readdate = max($readdate, $post['date']);
		Sql::query('UPDATE {pmthread_members} set readdate=? where thread=? and user=?', $readdate, $tid, Session::id());
	}
	
	$breadcrumbs = array(
		array('url' => Url::format('/members'), 'title' => __("Members")),
		array('user' => $user),
		array('url' => Url::format('/u/#-:/pm', $user['id'], $user['name']), 'title' => 'Messages'),
		array('url' => Url::format('/u/#-:/pm/#-:', $user['id'], $user['name'], $thread['id'], $thread['title']), 'title' => $thread['title']),
	);

	$actionlinks = array();

	// Retrieve the draft.
	$draft = Fetch::draft(4, $tid);
	$draft['tid'] = $tid;

	
	renderPage('pmthread.html', array(
		'participants' => $participants,
		'posts' => $posts, 
		'draft' => $draft,
		'canreply' => $me,

		'paging' => array(
			'perpage' => $ppp,
			'from' => $from,
			'total' => $thread['replies'] + 1, //+1 for the OP
			'base' => Url::format('/pm/#-:', $thread['id'], $thread['title']),
		),

		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => 'Messages',
	));
}