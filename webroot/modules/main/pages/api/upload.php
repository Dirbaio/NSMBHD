<?php
//page /api/upload

function request()
{
	Permissions::assertCanDoStuff();

	//TODO make configurable
	$maxSize = 10*1024*1024;

	if(!isset($_FILES['file']))
		fail(__("No file given."));

	$file = $_FILES['file'];

	if($file['tmp_name'] == "")
		fail(__('No file given.'));

	if($file['size'] == 0)
		fail(__('File is empty.'));

	if($file['size'] > $maxSize)
		fail(__('File is too large.'));

	$hash = md5_file($file['tmp_name']);
	$id = Util::randomString(10);

	$file['name'] = basename($file['name']);

	Sql::query('INSERT INTO {files} (id, hash, name, date, user) values (?,?,?,?,?)',
		$id, $hash, $file['name'], time(), Session::id());

	// Destination directory
	$dest = ModuleHandler::getRoot()."/uploads/".substr($hash, 0, 2);
	if(!is_dir($dest))
		mkdir($dest, 0777, true);  // recursive mkdir0

	$destfile = $dest.'/'.$hash;
	if(!is_file($destfile))
		copy($file['tmp_name'], $destfile);

	json(Url::format('/file/:/$', $id, $file['name']));
}