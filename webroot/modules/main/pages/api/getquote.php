<?php
//page /api/getquote

function request($pid)
{
	$post = Fetch::post($pid);
	$tid = $post['thread'];
	$thread = Fetch::thread($tid);
	$fid = $thread['forum'];
	$forum = Fetch::forum($fid);

	Permissions::assertCanViewForum($forum);
	Permissions::assertCanReply($thread, $forum);

	$poster = Fetch::user($post['user']);

	$text = $post['text'];
	if($post['deleted'])
		$text = __("Post is deleted");

	$text = str_replace('/me', '[b]* '.htmlspecialchars($poster['name']).'[/b]', $text);

	$reply = '[quote="'.$poster['name'].'" id="'.$post['id'].'"]'.$text.'[/quote]';

	json($reply);
}