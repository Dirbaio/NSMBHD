<?php

$path->shift();
$path->addStart(new PipeMenuLinkEntry(Settings::pluginGet("crumbsBoardLink"), "board"));
$path->addStart(new PipeMenuLinkEntry(Settings::pluginGet("crumbsBlogLink"), "blog"));


