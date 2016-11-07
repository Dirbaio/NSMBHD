<?php

class Url
{
	public static function getPath()
	{
		return UrlStyle::getPath();
	}

	public static function slugify($urlname)
	{
		$urlname = strtolower($urlname);
		$urlname = str_replace("&", "and", $urlname);
		$urlname = preg_replace("/[^a-zA-Z0-9]/", "-", $urlname);
		$urlname = preg_replace("/-+/", "-", $urlname);
		$urlname = preg_replace("/^-/", "", $urlname);
		$urlname = preg_replace("/-$/", "", $urlname);
		return $urlname;
	}

	public static function formatPath()
	{
		//Get the string and the args
		$args = func_get_args();
		while (is_array($args[0])) $args = $args[0];

		$format = array_shift($args);

		$format = preg_replace_callback('/[#:$]/', function($match) use (&$args) {
			$arg = array_shift($args);
			$char = $match[0];
			if($char == '$')
				return rawurlencode($arg);
			else if($char == '#')
				return (int) $arg;
			else
				return self::slugify($arg);
		}, $format);
		return $format;
	}

	public static function format()
	{
		$path = self::formatPath(func_get_args());
		return UrlStyle::getUrlForPath($path);
	}

	public static function setCanonicalUrl()
	{
		$path = self::formatPath(func_get_args());
		$currUrl = self::getPath();
		if($currUrl !== $path)
			self::redirectPermanently(UrlStyle::getUrlForPath($path));
	}

	public static function redirectPermanently($url)
	{
		header("HTTP/1.1 301 Moved Permanently"); 
		$maxage = 60*60*24*30;  // 1 month
		header('Pragma: public');
		header('Expires: '.gmdate('D, d M Y H:i:s ',time()+$maxage) . 'GMT');
		header('Cache-Control: public, max-age='.$maxage);
		header("Location: ".$url);
		die();
	}

	public static function redirect($url)
	{
		header("Location: ".$url);
		die();
	}

	public static function isHttps()
	{
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) || $_SERVER["SERVER_PORT"] == 443;
	}

	public static function getServerUrl()
	{
		global $boardroot;
		$https = self::isHttps();
		$stdport = $https?443:80;
		$port = "";
		if($stdport != $_SERVER["SERVER_PORT"] && $_SERVER["SERVER_PORT"])
			$port = ":".$_SERVER["SERVER_PORT"];
		return ($https?"https":"http") . "://" . $_SERVER['HTTP_HOST'] . $port ;
	}

	public static function getRequestUrl()
	{
		return self::getServerURL().$_SERVER['REQUEST_URI'];
	}

}