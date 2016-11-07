<?php

class ModuleHandler
{
	private static $loadedModules = null;
	private static $files = null;

	public static function init()
	{
		self::$loadedModules = array();
		self::$files = array();
	}

	public static function getRoot() {
		return __DIR__;
	}
	
	public static function loadModule($path)
	{
		$path = __DIR__.$path;

		if(!is_dir($path))
			throw new Exception("There is no module at path $path");

		if(is_file($path.'/lib/lib.php'))
			require($path.'/lib/lib.php');

		$moduleFiles = array();

		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file) 
		{
			if(endsWith($file, '.')) continue;

			$moduleFiles[] = str_replace('\\', '/', $file->getPathname());
		}

		sort($moduleFiles);
		
		foreach($moduleFiles as $file)
		{
			$logicalFile = substr($file, strlen($path));

			if(!isset(self::$files[$logicalFile]))
				self::$files[$logicalFile] = array();

			self::$files[$logicalFile][] = $file;
		}
	}

	public static function getFiles($file)
	{
		return self::$files[$file];
	}
	
	public static function getFile($file)
	{
		return self::$files[$file][count(self::$files[$file]) - 1];
	}

	public static function getFilesMatching($pattern)
	{
		$pattern = preg_quote($pattern);
		$pattern = str_replace('\*\*', '.*', $pattern);
		$pattern = str_replace('\*', '[^/]*', $pattern);
		$pattern = '#^'.$pattern.'$#';

		$res = array();
		foreach(self::$files as $file => $files)
			if(preg_match($pattern, $file))
				foreach($files as $entry)
					$res[] = $entry;

		return $res;
	}
	
	public static function toWebPath($file)
	{
		if(is_array($file))
			return array_map(array('ModuleHandler', 'toWebPath'), $file);
		else
			return substr($file, strlen(__DIR__)+1);
	}

}