<?php
//  AcmlmBoard XD - Administration hub page
//  Access: administrators


if($loguser['powerlevel'] < 3)
	Kill(__("You're not an administrator. There is nothing for you here."));
$lastUrlMinPower = 3;

$title = __("Query errors");

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Admin"), "admin"));
$crumbs->add(new PipeMenuLinkEntry(__("Query errors"), "queryerrors"));
makeBreadcrumbs($crumbs);


$q = Query('SELECT error, count(*) as ct, max(query) as q FROM {queryerrors} group by error limit 10');
while($e = fetch($q)) {
	var_dump($e);
}