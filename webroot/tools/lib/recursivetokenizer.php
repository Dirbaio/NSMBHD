<?php
// Recursive tokenizer

if (php_sapi_name() !== 'cli')
{
	die("This script is only intended for CLI usage.\n");
}

function recurse($callback, $ignore_whitespace = true)
{
	$directory = getcwd() . '/..';
	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file)
	{
		if ($file->isFile())
		{
			$filename = $file->getPathName();
			if (substr($file, -4) === '.php')
			{
				$file = token_get_all(file_get_contents($filename));
				$tokens = array();
				// Process the file to remove comments and whitespace
				if ($ignore_whitespace)
				{
					foreach ($file as $id => $token)
					{
						if (is_string($token) || $token[0] !== T_WHITESPACE && $token[0] !== T_COMMENT)
						{
							$tokens[] = $token;
						}
					}
				}
				else
				{
					$tokens = $file;
				}
				$filename = str_replace('\\', '/', preg_replace('{^.*[.]{2}[/\\\\]}', '', $filename));
				$callback($tokens, $filename);
			}
		}
	}
}
