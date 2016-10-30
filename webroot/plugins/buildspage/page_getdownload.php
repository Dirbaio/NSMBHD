<?php
	$ajaxPage = true;
	
	$rev = (int) $_GET["id"];
	$c = $_GET["c"];
	$code = doHash($_SERVER["REMOTE_ADDR"]."LOLfoahcmpughapw9hgcapuhcgn".$rev);
	
	if($c != $code)
		redirectAction("download");
	else
	{
		$path = "/home/nsmbhd/build/revs/nsmb-editor-$rev.zip";
		$fname = "nsmb-editor-$rev.zip";
		
		if(!file_exists($path))
			die("No such file.");
	
		$fsize = filesize($path);
		$parts = pathinfo($path);
		$ext = strtolower($parts["extension"]);
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
			header("Content-Disposition: attachment; filename=\"$fname\";");
		else
			header("Content-Disposition: filename=\"$fname\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$fsize);

		readfile($path);
	}
