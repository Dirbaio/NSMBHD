<?php

// Simple URL style, requires no extra configuration in the server.
class UrlStyle
{
	// Returns the url to access the given path
    public static function getUrlForPath($path)
    {
    	if($path == '/')
    		return './';
    	return './?'.$path;
    }

    // Returns the path for this request
    public static function getPath()
    {
		$res = $_SERVER['QUERY_STRING'];
		if($res[0] != '/')
			$res = '/' . $res;
		return $res;
    }
}
