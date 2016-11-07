<?php

class Session
{
	private static $user = null;
	private static $cookieName = 'woloderp';

	public static function load()
	{
		//delete expired sessions
		Sql::query('DELETE FROM {sessions} WHERE expiration != 0 and expiration < ?',
			time());

		if(isset($_COOKIE[self::$cookieName]) && $_COOKIE[self::$cookieName])
		{
			$session = Sql::querySingle('SELECT * FROM sessions WHERE id=?', Util::hash($_COOKIE[self::$cookieName]));

			if($session)
				self::loadUser($session['user']);
		}
	}

	private static function loadUser($id)
	{
		self::$user = Sql::fetch(Sql::query('SELECT * FROM users WHERE id=?', $id));
	}

	public static function start($user, $expiration = 5184000)
	{
		$sessionKey = Util::randomString();
		Sql::query('INSERT INTO sessions (id, user, expiration) VALUES (?, ?, ?)', Util::hash($sessionKey), $user, time()+$expiration);

		setcookie(self::$cookieName, $sessionKey, time()+$expiration, '/', null, Url::isHttps(), true);

		self::loadUser($user);
	}

	public static function end()
	{
		if(isset($_COOKIE[self::$cookieName]) && $_COOKIE[self::$cookieName])
		{
			Sql::query('DELETE FROM sessions WHERE id=?', Util::hash($_COOKIE[self::$cookieName]));
			setcookie(self::$cookieName, '', time(), '/', null, Url::isHttps(), true);
		}

		self::$user = null;
	}

	public static function get($what = NULL)
	{
		if($what)
			return self::$user[$what];
		
		return self::$user;
	}

	public static function id()
	{
		if(self::$user !== null)
			return self::$user['id'];

		return 0;
	}

	public static function isLoggedIn()
	{
		return self::$user != null;
	}

	public static function checkLoggedIn()
	{
		if(!self::isLoggedIn())
			fail('You are not logged in.');
	}
}