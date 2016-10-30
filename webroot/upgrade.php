<?php
error_reporting(E_ALL ^ E_NOTICE | E_STRICT);

require('config/database.php');
require('lib/debug.php');
require('lib/mysql.php');
require('lib/mysqlfunctions.php');
sqlConnect();
Upgrade();
echo "Done!";
?>
