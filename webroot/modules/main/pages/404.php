<?php

function request()
{
	$breadcrumbs = array(
	);

	$actionlinks = array(
	);

	renderPage('404.html', array(
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => '404 Not Found',
	));
}

