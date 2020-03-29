<?php
	$ajaxPage = 'no-content-type';

	$rev = (int) $_GET["id"];
	$c = $_GET["c"];
	$code = doHash($_SERVER["REMOTE_ADDR"]."LOLfoahcmpughapw9hgcapuhcgn".$rev);

	if($c != $code)
		redirectAction("download");
	else
	{
		$path = $dataDir."builds/nsmb-editor-$rev.zip";
		$fname = "nsmb-editor-$rev.zip";

		if(!file_exists($path))
			die("No such file.");

		$fsize = filesize($path);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=\"$fname\";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$fsize);

		readfile($path);
	}
