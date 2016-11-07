<?php 
//page /upload

//ABXD LEGACY
//page /uploader
//page /uploader.php

function request()
{
	Url::setCanonicalUrl('/upload');

	$breadcrumbs = array(
		array('url' => Url::format('/upload'), 'title' => __("UPLOAD MOAR STUFF")),
	);

	$actionlinks = array(
	);

	renderPage('upload.html', array(
		'breadcrumbs' => $breadcrumbs, 
		'actionlinks' => $actionlinks,
		'title' => 'UPLOAD MOAR STUFF',
	));
}