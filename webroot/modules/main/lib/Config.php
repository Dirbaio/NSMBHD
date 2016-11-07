<?php

class Config
{
	private static $config = null;

	public static function load($file)
	{
		require($file);
		self::$config = $config;
	}

	public static function get($what) 
	{
		if(self::$config == null)
			throw new Exception('Configuration not loaded');
		return self::$config[$what];
	}
}