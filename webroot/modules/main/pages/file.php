<?php 
//page /file/:id
//page /file/:id/$

//ABXD LEGACY
//page /get.php

function request($id)
{
	$file = Sql::querySingle('SELECT * FROM {files} WHERE id=?', $id);
	if(!$file)
		fail('No such file');

	Url::setCanonicalUrl('/file/:/$', $id, $file['name']);

	//Count downloads!
	Sql::query('UPDATE {files} SET downloads = downloads+1 WHERE id=?', $id);

	$dir = ModuleHandler::getRoot()."/uploads/".substr($file['hash'], 0, 2);
	$path = $dir.'/'.$file['hash'];

	if(!file_exists($path))
		fail('File found in DB but not on disk... :(');
	
	$fsize = filesize($path);
	$parts = explode(".", $file['name']);
	$ext = end($parts);
	$ext = strtolower($ext);
	$download = true;
	
	switch ($ext)
	{
		case 'gif': $ctype='image/gif'; $download = false; break;
		case 'apng':
		case 'png': $ctype='image/png'; $download = false; break;
		case 'jpeg':
		case 'jpg': $ctype='image/jpg'; $download = false; break;
		case 'css': $ctype='text/css'; $download = false; break;
		case 'txt': $ctype='text/plain'; $download = false; break;
		case 'swf': $ctype='application/x-shockwave-flash'; $download = false; break;
		case 'pdf': $ctype='application/pdf'; $download = false; break;
		default: $ctype='application/force-download'; break;
	} 


	$maxage = 60*60*24*30*12;  // 12 months
	$etag = $file['hash'];

	header('Pragma: public');
	header('Expires: '.gmdate('D, d M Y H:i:s ',time()+$maxage) . 'GMT');
	header('Cache-Control: public, max-age='.$maxage);

	if (isset($_SERVER['HTTP_IF_NONE_MATCH']) &&
		trim($_SERVER['HTTP_IF_NONE_MATCH'], "'\" ") == $etag)
	{
	    header('HTTP/1.1 304 Not Modified');
	}
	else
	{
		header('Content-Type: '.$ctype);
		if($download)
			$type = 'attachment';
		else
			$type = 'inline';

		header('Content-Disposition: '.$type.'; filename="'.$file['name'].'";');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.$fsize);

	    header("ETag: \"{$etag}\"");
		readfile($path);
	}

}