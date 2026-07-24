<?php
// CLI only -- run it as `launch.sh install`. nginx does not pass this file to
// php-fpm, because it has no authentication of its own.
if(PHP_SAPI !== 'cli')
	die("This script is CLI only.");

// Same as conf/php.ini: this codebase emits hundreds of E_WARNINGs per run, and
// they would bury the real output in the init container's log.
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);

// die() exits 0, which would let a broken init container pass as a success.
function fail($message)
{
	fwrite(STDERR, $message."\n");
	exit(1);
}

require('lib/config.php');
require('lib/debug.php');
require('lib/mysql.php');
require('lib/mysqlfunctions.php');

if(!sqlConnect())
	fail("Can't connect to the board database. Check the installation settings");
if(fetch(query("SHOW TABLES LIKE '{misc}'")))
	fail("Already installed! If you want to reinstall, delete all tables first. If you want to upgrade, run `launch.sh upgrade`.");

Upgrade();

Query("INSERT INTO `{misc}` (`views`, `hotcount`, `milestone`, `maxuserstext`) VALUES (0, 30, 'Nothing yet.', 'Nobody yet.');");
Import("lib/install/smilies.sql");
Import("lib/install/installDefaults.sql");

echo "Done!";
