<?php


$pid = (int)$_GET['id'];
$rPost = Query("select * from {posts} where id={0}", $pid);
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

$ppp = $loguser['postsperpage'];
if(!$ppp) $ppp = 20;
$from = (floor(FetchResult("SELECT COUNT(*) FROM {posts} WHERE thread={1} AND date<={2} AND id!={0}", $pid, $tid, $post['date']) / $ppp)) * $ppp;
$url = actionLink("thread", $thread["id"], $from?"from=$from":"", $thread["title"])."#".$pid;

header("HTTP/1.1 301 Moved Permanently");
header("Status: 301 Moved Permanently");
header("Location: ".$url);
die;

