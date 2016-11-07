<?php

class Validate
{
	public static function color(&$string)
	{
		if(!preg_match('/^#[a-f0-9]{3-6}$/i', $string))
			$string = '';
	}

	public static function email($string)
	{
	//	if(!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/', $string))
		if(!filter_var($string, FILTER_VALIDATE_EMAIL))
			fail("Invalid email!");
	}

	public static function notEmpty($string, $message)
	{
		if(trim($string) == false)
			fail($message);
	}

}