<?php
//  AcmlmBoard XD support - Database settings

function mygetenv($name, $default=null) {
    $res = getenv($name);
    if($res)
        return $res;
    if($default !== null)
        return $default;
    die('Missing envvar ' . $name);
}

$dbserv = mygetenv("MYSQL_HOST");
$dbuser = mygetenv("MYSQL_USER");
$dbpass = mygetenv("MYSQL_PASSWORD");
$dbname = mygetenv("MYSQL_DATABASE");

$logSqlErrors = true;
$debugMode = true;
$urlRewriting = true;

$stopForumSpamKey = mygetenv("ABXD_SFS_KEY", '');

$salt = mygetenv("ABXD_SALT");
