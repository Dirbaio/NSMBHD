<?php

session_start();

class Csrf
{
	public static function check()
	{
		if($_GET["token"])
			$token = $_GET["token"];
		else if($_POST["token"])
			$token = $_POST["token"];
		else if($_GET["state"])
			$token = $_GET["state"];
		else if($_POST["state"])
			$token = $_POST["state"];
		else
			fail("No token!");
		
		$goodtoken = self::get();
		if($token !== $goodtoken)
			fail("Bad token!");
	}

	public static function get()
	{
		if(!$_SESSION["token"])
			$_SESSION["token"] = Util::randomString();
		return $_SESSION["token"];
	}
}