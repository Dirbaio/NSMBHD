<?php
error_reporting(E_ALL ^ E_NOTICE | E_STRICT);

require('lib/config.php');
require('lib/debug.php');
require('lib/mysql.php');
require('lib/mysqlfunctions.php');

if(!sqlConnect())
	die("Can't connect to the board database. Check the installation settings");

Upgrade();

echo "Done!";
?>
