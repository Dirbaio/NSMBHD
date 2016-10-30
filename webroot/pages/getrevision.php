<?php

$ajaxPage = true;

$id = (int)$_GET["id"];

$rPost = Query("
		SELECT
			p.id, p.date, p.num, p.deleted, p.deletedby, p.reason, p.options, p.mood, p.ip,
			pt.text, pt.revision, pt.user AS revuser, pt.date AS revdate,
			u.(_userfields), u.(rankset,title,picture,posts,postheader,signature,signsep,lastposttime,lastactivity,regdate,globalblock),
			ru.(_userfields),
			du.(_userfields)
		FROM
			{posts} p
			LEFT JOIN {posts_text} pt ON pt.pid = p.id AND pt.revision = {1}
			LEFT JOIN {users} u ON u.id = p.user
			LEFT JOIN {users} ru ON ru.id=pt.user
			LEFT JOIN {users} du ON du.id=p.deletedby
		WHERE p.id={0}", $id, (int)$_GET['rev']);

if(NumRows($rPost))
	$post = Fetch($rPost);
else
	die(format(__("Unknown post ID #{0} or revision missing."), $id));

$qThread = "select forum from {threads} where id={0}";
$rThread = Query($qThread, $post['thread']);
$thread = Fetch($rThread);
$qForum = "select minpower from {forums} where id={0}";
$rForum = Query($qForum, $thread['forum']);
$forum = Fetch($rForum);
if($forum['minpower'] > $loguser['powerlevel'])
	die(__("No."));

echo makePostText($post);
