<?php 
//page /u/#-:/pm/new

function request()
{
	Permissions::assertCanDoStuff();
	Url::setCanonicalUrl('/u/#-:/pm/new', Session::id(), Session::get('name'));

	// Retrieve the draft.
	$draft = Fetch::draft(3, 0);

	$breadcrumbs = array(
		array('url' => Url::format('/members'), 'title' => __("Members")),
		array('user' => Session::get()),
		array('url' => Url::format('/u/#-:/pm', Session::id(), Session::get('name')), 'title' => __('Messages')),
		array('url' => Url::format('/u/#-:/pm/new', Session::id(), Session::get('name')), 'title' => __('New'), 'weak' => true),
	);

	$actionlinks = array(
	);

	renderPage('newprivate.html', array(
		'draft' => $draft,

		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => $forum['title'],
	));
}

