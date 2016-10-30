<?php
if (php_sapi_name() !== 'cli')
{
	die("This script is only intended for CLI usage.\n");
}

function search_for_function($tokens, $filename)
{
	global $function, $found;
	foreach ($tokens as $id => $token)
	{
		if (is_array($token) && $token[0] === T_FUNCTION && is_array($tokens[$id + 1]) && strtolower($tokens[$id + 1][1]) === $function)
		{
			$found = true;
			echo("$function() is defined in $filename, line {$tokens[$id + 1][2]}.\n");
		}
	}
}

require 'lib/recursivetokenizer.php';
if (!isset($argv[1]))
{
	die("Usage: $argv[0] [FUNCTION]\n");
}
$found = false;
$function = strtolower($argv[1]);
$php_functions = get_defined_functions();
if (in_array($function, $php_functions['internal']))
{
	die("$function() is PHP core function.\n");
}
recurse('search_for_function');
if (!$found)
{
	echo "$argv[1]() wasn't found.\n";
}
