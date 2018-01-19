<?php
error_reporting(E_ALL ^ E_NOTICE | E_STRICT);

require('lib/config.php');
require('lib/debug.php');
require('lib/mysql.php');
require('lib/mysqlfunctions.php');

if(!sqlConnect())
	die("Can't connect to the board database. Check the installation settings");
if(fetch(query("SHOW TABLES LIKE '{misc}'")))
	die("Already installed! If you want to reinstall, delete all tables first. If you want to upgrade, visit /upgrade.php");

Upgrade();

Query("INSERT INTO `{misc}` (`views`, `hotcount`, `milestone`, `maxuserstext`) VALUES (0, 30, 'Nothing yet.', 'Nobody yet.');");
Import("lib/install/smilies.sql");
Import("lib/install/installDefaults.sql");

echo "Done!";
