<?php 
//page /u/#id/edit
//page /u/#id-:/edit

//ABXD LEGACY
//page /editprofile/#id
//page /editprofile/#id-:
//page /editprofile.php

function request($id)
{
	$user = Fetch::user($id);

	Url::setCanonicalUrl('/u/#-:/edit', $user['id'], $user['name']);


	$breadcrumbs = array(
		array('url' => Url::format('/members'), 'title' => __("Members")),
		array('user' => $user),
		array('url' => Url::format('/u/#-:/edit', $user['id'], $user['name']), 'title' => __('Edit profile'), 'weak' => true),
	);

	$actionlinks = array();

	renderPage('member.html', array(
		'user' => $user,
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => $forum['title'],
	));
}