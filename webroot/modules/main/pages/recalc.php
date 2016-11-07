<?php 
//page /recalc

function usectime()
{
	$t = gettimeofday();
	return $t['sec'] + ($t['usec'] / 1000000);
}

function fix($what, $query)
{
	echo $what, '... ';
	$start = usectime();
	$affected = Sql::queryAffected($query);
	$len = usectime() - $start;

	echo sprintf('%1.3f', $len), 's ';
	if($affected)
		echo $affected, ' rows affected. ';
	echo "\n";
}

function request()
{
	// Post stuff

	// post num -- This should work, but it's too slow :(
	/*
	fix('Post num', 
		'UPDATE posts p 
		JOIN (
			SELECT p1.id as id, count(p2.id)+1 as num
			FROM posts p1
			LEFT JOIN posts p2 on p1.user=p2.user and p2.id < p1.id
			GROUP BY p1.id
		) as m on m.id=p.id
		SET p.num = m.num
	');*/

	fix('Post currentrevision', 
		'UPDATE posts p
		SET currentrevision=(
			SELECT MAX(revision) 
			FROM posts_text pt 
			WHERE pt.pid = p.id)');

	fix('Post editdate', 
		'UPDATE posts p
		SET editdate=GREATEST(p.date, (
			SELECT pt.date 
			FROM posts_text pt 
			WHERE pt.pid = p.id AND pt.revision = p.currentrevision))');

	// Thread stuff
	fix('Thread replies', 
		'UPDATE {threads} t
		SET replies = (SELECT COUNT(*) FROM {posts} p WHERE p.thread = t.id) - 1');

	fix('Thread first post id, date, user', 
		'UPDATE threads t 
		LEFT JOIN (
			SELECT thread, MIN(id) as minid
			FROM posts
			GROUP BY thread) AS tmp ON tmp.thread = t.id
		LEFT JOIN {posts} p ON tmp.minid=p.id
		SET t.firstpostid=p.id, t.date=p.date, t.user=p.user');

	fix('Thread last post id, date, user', 
		'UPDATE threads t 
		LEFT JOIN (
			SELECT thread, MAX(id) as maxid
			FROM posts
			GROUP BY thread) AS tmp ON tmp.thread = t.id
		LEFT JOIN posts p ON p.id=tmp.maxid
		SET lastpostid=p.id, lastpostuser=p.user, lastpostdate=p.editdate');

	// Forum stuff
	fix('Forum threads', 
		'UPDATE {forums} f
		SET numthreads = (SELECT COUNT(*) FROM {threads} t WHERE t.forum = f.id)');

	fix('Forum posts', 
		'UPDATE {forums} f 
		SET numposts = (SELECT SUM(replies+1) FROM {threads} t WHERE t.forum = f.id)');

	fix('Forum last post id, date, user', 
		'UPDATE forums f 
		LEFT JOIN (
			SELECT forum, MAX(lastpostdate) as maxdate
			FROM threads
			GROUP BY forum) AS tmp ON tmp.forum = f.id
		LEFT JOIN threads t ON t.lastpostdate=tmp.maxdate
		SET f.lastpostid=t.lastpostid, f.lastpostuser=t.lastpostuser, f.lastpostdate=t.lastpostdate');

	// User stuff
	fix('User postcount, threadcount', 
		'UPDATE {users} u 
		SET 
			posts = (SELECT COUNT(*) FROM {posts} p WHERE p.user = u.id),
			threads = (SELECT COUNT(*) FROM {threads} t WHERE t.user = u.id)
		');

	fix('User lastpostid', 
		'UPDATE {users} u 
		SET 
			lastpostid = coalesce((SELECT MAX(id) FROM {posts} p WHERE p.user = u.id), 0)
		');

	fix('User lastpostdate', 
		'UPDATE {users} u 
		SET 
			lastpostdate = coalesce((SELECT date FROM {posts} p WHERE p.id = u.lastpostid), 0)
		');

}