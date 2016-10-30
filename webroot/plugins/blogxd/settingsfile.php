<?php
	$settings = array(
		"righttext" => array (
			"type" => "textbbcode",
			"default" => "Hello, this is some test thing",
			"name" => "Right column text"
		),
		"forum" => array(
			"type" => "forum",
			"name" => "Blog forum",
			"help" => "Blog entries will be the threads from that forum. You should restrict who can post threads in it."
		),
		"pagename" => array(
			"type" => "text",
			"name" => "Blog page name",
			"default" => "Blog",
			"help" => "The text of the link that will be added to the top menu."
		),
		"changeCrumbs" => array(
			"type" => "boolean",
			"name" => "Breadcrumbs override",
			"default" => "true",
		),
		"crumbsBlogLink" => array(
			"type" => "text",
			"name" => "Text of link to blog in the breadcrumbs",
			"default" => "Main",
		),
		"crumbsBoardLink" => array(
			"type" => "text",
			"name" => "Text of link to board in the breadcrumbs",
			"default" => "Forums",
		),
	);
?>
