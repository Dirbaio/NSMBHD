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
		print $row["classid"]; //Id
		print "#";
		print rep($row[1]);  //Name
		print "}\n";
	}

	$getsprites = mysql_query('select distinct classid, name from sprites order by classid');
	while ($row = mysql_fetch_array($getsprites))
	{
		printSpriteRow($row);
	}
	print "end\n\n";

?>



