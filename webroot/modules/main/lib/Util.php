<?php

class Util
{

	public static function randomString($length = 32)
	{
		$cstrong = false;
	    $bytes = openssl_random_pseudo_bytes($length, $cstrong);
	    
	    if(!$cstrong)
	    	fail("Crypto fail OMG WHY?!?");
	    
	    return bin2hex($bytes);
	}

	public static function hash($lol)
	{
		return hash('sha256', $lol);
	}
}


function json($data)
{
	header('Content-Type: application/json');
	echo json_encode($data);
	die();
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function __($what)
{
	return $what;
}