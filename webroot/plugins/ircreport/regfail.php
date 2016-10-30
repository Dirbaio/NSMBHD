<?php

$c1 = "\003".ircColor(Settings::pluginGet("color1"));
$c2 = "\003".ircColor(Settings::pluginGet("color2"));

ircReport("$c2register blocked: reason=$c1".$reason."$c2, user=$c1".$_POST['user']."$c2, email=$c1".$_POST['email']."$c2, ip=$c1".$_SERVER['REMOTE_ADDR'], -1);

