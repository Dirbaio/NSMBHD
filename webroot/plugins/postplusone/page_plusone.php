<?php
$ajaxPage = true;

if($_GET["key"] != $loguser["token"])
	die("Nope!");

$pid = (int)$_GET["id"];

$post = Fetch(Query("SELECT * FROM {posts} WHERE id = {0}", $pid));
if(!$post)
	die("Unknown post");
if($post["user"] == $loguserid)
	die("Nope!");

$thread = Fetch(Query("SELECT * FROM {threads} WHERE id = {0}", $post["thread"]));
if(!$thread)
	die("Unknown thread");

//if($thread["closed"])
//	die(__("Thread is closed"));

$vote = Fetch(Query("SELECT * FROM {postplusones} WHERE post = {0} AND user = {1}", $pid, $loguserid));
if($vote)
	die(__("You already +1'd this"));

if($loguser["powerlevel"] < 0)
	die(__("You are banned and can't give +1's to posts."));

Query("UPDATE {posts} SET postplusones = postplusones+1 WHERE id = {0} LIMIT 1", $pid);
Query("UPDATE {users} SET postplusones = postplusones+1 WHERE id = {0} LIMIT 1", $post["user"]);
Query("UPDATE {users} SET postplusonesgiven = postplusonesgiven+1 WHERE id = {0} LIMIT 1", $loguserid);
Query("INSERT INTO {postplusones} (user, post) VALUES ({0}, {1})", $loguserid, $pid);
$post["postplusones"]++;

recalculateKarma($post["user"]);

echo formatPlusOnes($post["postplusones"]);


