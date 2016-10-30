<?php
if (php_sapi_name() !== 'cli')
{
	die("This script is only intended for CLI usage.\n");
}

// Generator for en_US language file
function find_strings($tokens, $filename)
{
	global $messages, $languagePack;

	$filenameInserted = false;
	// Now search for __() calls
	foreach ($tokens as $id => $token)
	{
		// __() declaration
		if (is_array($token) && $token[1] === '__' && $tokens[$id + 1] === '(')
		{
			if ($tokens[$id + 2][0] === T_CONSTANT_ENCAPSED_STRING && ($tokens[$id + 3] === ')' || $tokens[$id + 3] === ','))
			{
				$thetoken = $tokens[$id + 2][1];
				$string = eval('return '.$thetoken.';');
				if (!isset($messages[$string]))
				{
					if (!$filenameInserted)
					{
						echo "\n// $filename\n";
						$filenameInserted = true;
					}

					$translation = "";
					if(isset($languagePack[$string]))
						$translation = $languagePack[$string];

					echo var_export($string, true), " =>\n", var_export($translation, true), ",\n\n";
				}
				// Hash lookups are fast, so why not abuse this structure?
				$messages[$string] = true;
			}
			elseif ($tokens[$id - 1][0] !== T_FUNCTION)
			{
				$line = isset($tokens[$id + 2][2]) ? $tokens[$id + 2][2] : $token[2];
				die("The __() call in $filename at line $line is not constant value\n");
			}
		}
	}
}

require 'lib/recursivetokenizer.php';

if(!isset($argv[1]))
	die("Usage: generatetranslation.php <langName>|all\n");

if($argv[1] == "all")
{
	if ($handle = opendir('../lib/lang/')) {
		while (false !== ($entry = readdir($handle)))
		{
			if(preg_match("/^(.*)_lang\\.php$/", $entry, $matches))
			{
				$lang = $matches[1];
				updateLanguage($lang);
			}
		}

		closedir($handle);
	}
}
else
	updateLanguage($argv[1]);

print "Done!\n";

function updateLanguage($lang)
{
	global $messages, $languagePack;
	echo $lang, "... ";
	ob_start();
	$messages = array();

	$languagePack = array();
	$langFile = "../lib/lang/".$lang."_lang.php";
	if(file_exists($langFile))
		include $langFile;

	echo "<?php\n\$languagePack = array(\n";

	recurse('find_strings');

	$textWritten = false;
	foreach($languagePack as $original => $translated)
	{
		if(!isset($messages[$original]))
		{
			if(!$textWritten)
				echo "\n// Strings no longer used\n";
			$textWritten = true;
			$translated = trim($translated);
			if($translated)
				echo var_export($original, true), " =>\n", var_export($translated, true), ",\n\n";
		}
	}

	echo ");\n";

	$stuff = ob_get_contents();
	ob_end_clean();
	file_put_contents($langFile, $stuff);
	echo "Ok.\n";
}
