<?php 
//page /#/#id
//page /#/#id-:
//page /#-:/#id
//page /#-:/#id-:

//page /#/#id/p#from
//page /#/#id-:/p#from
//page /#-:/#id/p#from
//page /#-:/#id-:/p#from

//ABXD LEGACY
//page /thread/#id
//page /thread/#id-:
//page /thread.php

function request($id, $from=0)
{
	$tid = $id;
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);

	Permissions::assertCanViewForum($forum);

	if($from == 0)
		Url::setCanonicalUrl('/#-:/#-:', $forum['id'], $forum['title'], $thread['id'], $thread['title']);
	else
		Url::setCanonicalUrl('/#-:/#-:/p#', $forum['id'], $forum['title'], $thread['id'], $thread['title'], $from);

	$ppp = 20;

	$posts = Sql::queryAll(
		'SELECT
			p.*,
			pt.text, pt.revision, pt.user AS revuser, pt.date AS revdate,
			userposted.(_userfields,rankset,title,picture,posts,postheader,signature,signsep,lastposttime,lastactivity,regdate,globalblock),
			useredited.(_userfields),
			userdeleted.(_userfields)
		FROM
			{posts} p
			LEFT JOIN {posts_text} pt ON pt.pid = p.id AND pt.revision = p.currentrevision
			LEFT JOIN {users} userposted ON userposted.id = p.user
			LEFT JOIN {users} useredited ON useredited.id = pt.user
			LEFT JOIN {users} userdeleted ON userdeleted.id = p.deletedby
		WHERE thread=?
		ORDER BY date ASC LIMIT ?, ?', $tid, $from, $ppp);

	// Set postlinks

	foreach($posts as &$post)
	{
		$links = array();
		if($post['deleted'])
		{
			if(Permissions::canDeletePost($post, $thread, $forum))
			{
				//$links[] = array('title' => __('View'));
				$links[] = array('title' => __('Undelete'), 'ng' => 'doAction("api/deletepost", {pid:'.$post['id'].', del:0})');
			}
		}
		else
		{
			$links[] = array('url' => Url::format('/post/#', $post['id']), 'title' => __('Link'));

			if(Permissions::canReply($thread, $forum))
				$links[] = array('title' => __('Quote'), 'ng' => 'quote('.$post['id'].')');
			if(Permissions::canEditPost($post, $thread, $forum))
				$links[] = array('title' => __('Edit'), 'url' => Url::format('/post/#/edit', $post['id']));
			if(Permissions::canDeletePost($post, $thread, $forum))
				$links[] = array('title' => __('Delete'), 'ng' => 'deletePost('.$post['id'].', 1)');
		}

		$post['links'] = $links;
	}

	//WTF PHP 
	unset($post);


	// Update thread views
	Sql::query('UPDATE {threads} SET views=views+1 WHERE id=?', $tid);


	// Set read date to the max date of the posts displayed in this page.
	// If the user is not viewing the last page, he will still see the unread marker.
	$readdate = 0;
	foreach($posts as $post)
	{
		$readdate = max($readdate, $post['date']);

		//Last post's editdate also counts.
		if($post['id'] == $thread['lastpostid'])
			$readdate = max($readdate, $post['editdate']);
	}

	Sql::query(
		'INSERT INTO {threadsread} (id,thread,date) VALUES (?,?,?)
		ON DUPLICATE KEY UPDATE date = GREATEST(date, ?)',
		Session::id(), $tid, $readdate, $readdate);


	// Poll handling.
	if($thread['poll'])
		$poll = Fetch::pollComplete($thread['poll']);
	else
		$poll = NULL;


	// Retrieve the draft.
	$draft = Fetch::draft(0, $tid);
	$draft['tid'] = $tid;

	//Layout stuff
	$breadcrumbs = array(
		array('url' => Url::format('/#-:', $forum['id'], $forum['title']), 'title' => $forum['title']),
		array('url' => Url::format('/#-:/#-:', $forum['id'], $forum['title'], $thread['id'], $thread['title']), 'title' => $thread['title']),
	);

	$actionlinks = array(
	);

	if(Permissions::canMod($forum)) {
		if($thread['closed'])
			$actionlinks[] = array('title' => __('Open'), 'ng' => 'doAction("/api/openthread", {tid: '.$tid.'})');
		else
			$actionlinks[] = array('title' => __('Close'), 'ng' => 'doAction("/api/closethread", {tid: '.$tid.'})');

		if($thread['sticky'])
			$actionlinks[] = array('title' => __('Unstick'), 'ng' => 'doAction("/api/unstickthread", {tid: '.$tid.'})');
		else
			$actionlinks[] = array('title' => __('Stick'), 'ng' => 'doAction("/api/stickthread", {tid: '.$tid.'})');
	}

	if(Permissions::canEditThread($thread, $forum))
		$actionlinks[] = array('title' => __('Rename'), 'ng' => 'renameThread('.$tid.')');

	//Render page
	renderPage('thread.html', array(
		'forum' => $forum, 
		'thread' => $thread, 
		'posts' => $posts, 
		'poll' => $poll, 
		'draft' => $draft,
		'canreply' => Permissions::canReply($thread, $forum),
		'paging' => array(
			'perpage' => $ppp,
			'from' => $from,
			'total' => $thread['replies'] + 1, //+1 for the OP
			'base' => Url::format('/#-:/#-:', $forum['id'], $forum['title'], $thread['id'], $thread['title']),
		),
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => $forum['title'],
	));

}

