<?php

if($loguser['powerlevel'] < 3)
	Kill(__("Access denied."));

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Admin"), "admin"));
$crumbs->add(new PipeMenuLinkEntry(__("Log"), "log"));
makeBreadcrumbs($crumbs);

doLogList("1");
