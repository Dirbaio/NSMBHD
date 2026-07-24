<?php
// CLI only -- run it as `launch.sh upgrade`. nginx does not pass this file to
// php-fpm, because it has no authentication of its own.
if(PHP_SAPI !== 'cli')
	die("This script is CLI only.");

// Same as conf/php.ini: this codebase emits hundreds of E_WARNINGs per run, and
// they would bury the real output in the init container's log.
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);

require('lib/config.php');
require('lib/debug.php');
require('lib/mysql.php');
require('lib/mysqlfunctions.php');

// die() exits 0, which would let a broken init container pass as a success.
if(!sqlConnect())
{
	fwrite(STDERR, "Can't connect to the board database. Check the installation settings\n");
	exit(1);
}

Upgrade();

echo "Done!";
?>
