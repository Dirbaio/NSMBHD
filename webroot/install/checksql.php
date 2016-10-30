<?php
$sqlServ = $_POST['sqlServerAddress'];
if (!$sqlServ) die("No SQL server address was specified. Note that the default \"localhost\" is a plceholder.");
$sqlUser = $_POST['sqlUserName'];
$sqlPass = $_POST['sqlPassword'];
$sqlData = $_POST['sqlDbName'];

$dblink = mysqli_init();
// 2 seconds timeout, will make errors noticed more quickly
$dblink->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);
if (!@$dblink->real_connect($sqlServ, $sqlUser, $sqlPass, null))
{
	die("Connect error ({$dblink->connect_errno}): {$dblink->connect_error}");
}

if (isset($_GET['attemptCreate']))
{
	if ($dblink->query("CREATE DATABASE $sqlData"))
	{
		die("Successfully created the database. You should be good to go.");
	}
	else
	{
		die("Error: {$dblink->error}");
	}
}

if ($dblink->select_db($sqlData))
{
	print "Connected successfully. Your settings are valid.";
}
else
{
	die("The database was not found. <button onclick=\"checkSqlConnection(true);\">Attempt to create it</button>");
}
