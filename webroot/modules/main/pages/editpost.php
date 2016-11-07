<?php 
//page /post/#pid/edit

//ABXD LEGACY
//page /editpost/#pid
//page /editpost.php

function request($pid)
{
	$post = Fetch::post($pid);
	$tid = $post['thread'];
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);
	
	Permissions::assertCanViewForum($forum);
	Permissions::assertCanEditPost($post, $thread, $forum);
	
	// Retrieve the draft. Fill it with the post text if none.
	$draft = Fetch::draft(2, $pid);
	if(!$draft['text'])
		$draft['text'] = $post['text'];
	
	$draft['pid'] = $pid;

	//Layout stuff
	$breadcrumbs = array(
		array('url' => Url::format('/#-:', $forum['id'], $forum['title']), 'title' => $forum['title']),
		array('url' => Url::format('/#-:/#-:', $forum['id'], $forum['title'], $thread['id'], $thread['title']), 'title' => $thread['title']),
		array('url' => Url::format('/post/#/edit', $pid), 'title' => __('Edit post'), 'weak' => true),
	);

	$actionlinks = array(
	);

	//Render page
	renderPage('editpost.html', array(
		'draft' => $draft,
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => __('Edit post'),
	));
}