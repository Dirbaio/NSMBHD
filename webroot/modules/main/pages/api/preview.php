<?php
//page /api/preview

function request($text='')
{
	$post = array(
		'userposted' => Session::get(),
		'text' => $text,
		'num' => 'inf',
		'id' => 'inf',
	);

	ob_start();

	Template::render('components/postItem.html', array('post' => $post));

	$reply = ob_get_contents();
	ob_end_clean();

	json($reply);
}