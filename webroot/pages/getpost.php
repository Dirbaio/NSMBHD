<?php

$ajaxPage = true;

$id = (int)$_GET["id"];

$rPost = Query("
		SELECT
			p.id, p.date, p.num, p.deleted, p.deletedby, p.reason, p.options, p.mood, p.ip,
			pt.text, pt.revision, pt.user AS revuser, pt.date AS revdate,
			u.(_userfields), u.(rankset,title,picture,posts,postheader,signature,signsep,lastposttime,lastactivity,regdate,globalblock),
			ru.(_userfields),
			du.(_userfields),
			f.id fid
		FROM
			{posts} p
			LEFT JOIN {posts_text} pt ON pt.pid = p.id AND pt.revision = p.currentrevision
			LEFT JOIN {users} u ON u.id = p.user
			LEFT JOIN {users} ru ON ru.id=pt.user
			LEFT JOIN {users} du ON du.id=p.deletedby
			LEFT JOIN {threads} t ON t.id=p.thread
			LEFT JOIN {forums} f ON f.id=t.forum
		WHERE p.id={0}", $id);


if (!NumRows($rPost))
	die(__("Unknown post ID."));
$post = Fetch($rPost);

if (!CanMod($loguserid, $post['fid']) && $loguserid != $post["u_id"])
	die(__("No."));

echo MakePost($post, $_GET['o'] ? POST_DELETED_SNOOP : POST_NORMAL, array('tid'=>$post['thread'], 'fid'=>$post['fid']));

