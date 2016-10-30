<?php
if(isset($_GET['error'])) die("Please use get.php");

$ajaxPage = TRUE;
include("lib/common.php");

$full = GetFullURL();
$here = substr($full, 0, strrpos($full, "/"))."/";

if(isset($_GET['id']))
	$entry = Query("select * from files where id = {0}", $_GET['id']);
else if(isset($_GET['file']))
	$entry = Query("select * from files where name = {0}", $_GET['file']);
else
	die("Nothing specified.");

if(!NumRows($entry))
	die(__("No such file."));

$entry = Fetch($entry);

//Count downloads!
Query("update files set downloads = downloads+1 where id = {0}", $entry['id']);

$path = $dataDir."uploads/".substr($entry['hash'], 0, 2).'/'.$entry['hash'];
if(!file_exists($path))
	die("No such file.");

$fsize = filesize($path);
$ext = strtolower(end(explode(".", $entry['name'])));
$download = true;

switch ($ext)
{
	case "gif": $ctype="image/gif"; $download = false; break;
	case "apng":
	case "png": $ctype="image/png"; $download = false; break;
	case "jpeg":
	case "jpg": $ctype="image/jpg"; $download = false; break;
	case "css": $ctype="text/css"; $download = false; break;
	case "txt": $ctype="text/plain"; $download = false; break;
	case "swf": $ctype="application/x-shockwave-flash"; $download = false; break;
	case "pdf": $ctype="application/pdf"; $download = false; break;
	default: $ctype="application/force-download"; break;
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
header("Content-Type: ".$ctype);
if($download)
	header("Content-Disposition: attachment; filename=\"".$entry['name']."\";");
else
	header("Content-Disposition: filename=\"".$entry['name']."\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$fsize);

readfile($path);

?>
