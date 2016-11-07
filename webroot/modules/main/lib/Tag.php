<?php

class Tag
{
	private static $cookieName = "iloveyou";

	public static function get()
	{
		if(!$_COOKIE[self::$cookieName])
		{
			$tag = Util::randomString();
			$forever = 2000000000;
			setcookie(self::$cookieName, $tag, $forever, "/", null, Util::isHttps(), true);
			$_COOKIE[self::$cookieName] = $tag;
		}
		return $_COOKIE[self::$cookieName];
	}
}
