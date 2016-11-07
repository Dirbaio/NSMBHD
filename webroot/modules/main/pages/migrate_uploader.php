<?php 
//page /migrate_uploader

function request()
{
	$files = Sql::query('SELECT * FROM {uploader}');
	while($file = Sql::fetch($files))
	{
		echo $file['id'], ' ', $file['filename'], "...\n";

		if($file['private'])
			$oldfile = "uploader/".$file['user']."/".$file['filename'];
		else
			$oldfile = "uploader/".$file['filename'];

		if(!is_file($oldfile)) {
			echo "Not found!\n";
			continue;
		}


		$hash = md5_file($oldfile);
		$id = $file['id'];

		Sql::query('INSERT INTO {files} (id, hash, name, date, user) values (?,?,?,?,?)',
			$id, $hash, $file['filename'], time(), $file['user']);

		// Destination directory
		$dest = ModuleHandler::getRoot()."/uploads/".substr($hash, 0, 2);
		if(!is_dir($dest))
			mkdir($dest, 0777, true);  // recursive mkdir0

		$destfile = $dest.'/'.$hash;
		if(!is_file($destfile))
			copy($oldfile, $destfile);


	}
}