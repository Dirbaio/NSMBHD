<?php

// Rewriting UrlHandler. Generates fancier URLs but requires additional server config.
class UrlStyle
{
	// Returns the url to access the given path
    public static function getUrlForPath($path)
    {
    	return $path;
    }

    // Returns the path for this request
    public static function getPath()
    {
		// Allow running from CLI
		global $argv;
		if(php_sapi_name() === 'cli')
		{
			if($argv[1])
				return $argv[1];
			else
				return '/';
		}

		// Legacy ABXD compat
		if(!empty($_GET['page']))
			return '/'.$_GET['page'].'.php';

		// Try to figure out the pathinfo
		if(!empty($_SERVER['PATH_INFO']))
			return $_SERVER['PATH_INFO'];

		if(!empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php')
			return $_SERVER['ORIG_PATH_INFO'];

		if(!empty($_SERVER['REQUEST_URI']))
			return (strpos($_SERVER['REQUEST_URI'], '?') > 0) ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];

		return '/';
    }
}
