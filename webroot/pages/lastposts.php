<?php

$title = __("Last posts");

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Last posts"), "lastposts"));
makeBreadcrumbs($crumbs);

doLastPosts(false, 100);

