<?php
$uid = (int)$_GET['id'];

$rUser = Query("select * from {users} where id={0}", $uid);
if(NumRows($rUser))
	$user = Fetch($rUser);
else
	Kill(__("Unknown user ID."));

$title = __("Thread list");

$uname = $user["name"];
if($user["displayname"])
	$uname = $user["displayname"];

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Member list"), "memberlist"));
$crumbs->add(new PipeMenuHtmlEntry(userLink($user)));
$crumbs->add(new PipeMenuTextEntry(__("Threads")));
makeBreadcrumbs($crumbs);

$total = FetchResult("SELECT
						count(*)
					FROM
						{threads} t
						LEFT JOIN {forums} f ON f.id=t.forum
					WHERE t.user={0} AND f.minpower <= {1}", $uid, $loguser["powerlevel"]);

$tpp = $loguser['threadsperpage'];
if(isset($_GET['from']))
	$from = (int)$_GET['from'];
else
	$from = 0;

if(!$tpp) $tpp = 50;

$rThreads = Query("	SELECT
						t.*,
						f.(title, id),
						".($loguserid ? "tr.date readdate," : '')."
						su.(_userfields),
						lu.(_userfields)
					FROM
						{threads} t
						".($loguserid ? "LEFT JOIN {threadsread} tr ON tr.thread=t.id AND tr.id={3}" : '')."
						LEFT JOIN {users} su ON su.id=t.user
						LEFT JOIN {users} lu ON lu.id=t.lastposter
						LEFT JOIN {forums} f ON f.id=t.forum
					WHERE t.user={0} AND ".forumAccessControlSql()."
					ORDER BY lastpostdate DESC LIMIT {1u}, {2u}", $uid, $from, $tpp, $loguserid);

$numonpage = NumRows($rThreads);

$pagelinks = PageLinks(actionLink("listthreads", $uid, "from="), $tpp, $from, $total);

if($pagelinks)
	echo "<div class=\"smallFonts pages\">".__("Pages:")." ".$pagelinks."</div>";

$ppp = $loguser['postsperpage'];
if(!$ppp) $ppp = 20;

if(NumRows($rThreads))
	echo listThreads($rThreads, false, true);
else
	Alert(__("No threads found."), __("Error"));

if($pagelinks)
	Write("<div class=\"smallFonts pages\">".__("Pages:")." {0}</div>", $pagelinks);

