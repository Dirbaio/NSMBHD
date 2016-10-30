<?php
	header("Content-type: text/plain");

	$noAutoHeader = TRUE;
	$noViewCount = TRUE;
	$noOnlineUsers = TRUE;
	$noFooter = TRUE;
	$ajax = TRUE;
	include("lib/common.php");


	function rep($str)
	{
		$order   = array("\r\n", "\n", "\r");
		$str = str_replace($order, "@", $str);
		$str = str_replace(";", ":", $str);
		return $str;
	}

	function printSpriteRow($row)
	{
		print "{";
		print $row["id"]; //Id
		print "#";
		print $row["classid"];  //Name
		print "}\n";
	}

	$getsprites = mysql_query('select distinct classid, id from sprites order by id');
	while ($row = mysql_fetch_array($getsprites))
	{
		printSpriteRow($row);
	}
	print "end\n\n";

?>



