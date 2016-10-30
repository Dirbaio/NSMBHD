<?php

$ajaxPage = 1;

if(!isset($_POST['id']))
	Kill(__("Post ID unspecified."));
if ($_POST['key'] != $loguser['token']) 
	Kill(__("No."));

$pid = (int)$_POST['id'];

$rPost = Query("SELECT * FROM {posts} WHERE id={0}", $pid);

if(NumRows($rPost))
	$post = Fetch($rPost);
else
	Kill(__("Unknown post ID."));

$tid = $post['thread'];

$rThread = Query("select * from {threads} where id={0}", $tid);
if(NumRows($rThread))
	$thread = Fetch($rThread);
else
	Kill(__("Unknown thread ID."));

$rFora = Query("select * from {forums} where id={0}", $thread['forum']);
if(NumRows($rFora))
	$forum = Fetch($rFora);
else
	Kill(__("Unknown forum ID."));

$fid = $forum['id'];

if(!CanMod($loguserid,$fid))
	Kill(__("You're not allowed to delete posts."));

$del = (int)$_POST['delete'];

if($del == 1)
{
	Query("update {posts} set deleted=1,deletedby={0},reason={1} where id={2} limit 1", $loguserid, $_POST['reason'], $pid);
	logAction('deletepost', array('forum' => $fid, 'thread' => $tid, 'user2' => $post["user"], 'post' => $pid));
	recalculateKarma($post["user"]);
}
else if($del == 2)
{
	Query("update {posts} set deleted=0 where id={0} limit 1", $pid);
	logAction('undeletepost', array('forum' => $fid, 'thread' => $tid, 'user2' => $post["user"], 'post' => $pid));
	recalculateKarma($post["user"]);
}




