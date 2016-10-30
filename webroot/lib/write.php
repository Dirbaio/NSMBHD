<?php

// TODO: Kill this.

if(!function_exists('format'))
{
	function format()
	{
		$argc = func_num_args();
		if($argc == 1)
			return func_get_arg(0);
		$args = func_get_args();
		$output = $args[0];
		for($i = 1; $i < $argc; $i++)
		{
			$splicethis = preg_replace("'\{([0-9]+)\}'", "&#x7B;\\1&#x7D;", $args[$i]);
			$output = str_replace("{".($i-1)."}", $splicethis, $output);
		}
		return $output;
	}

	function write()
	{
		$argc = func_num_args();
		if($argc == 0)
			return func_get_arg(0);
		$args = func_get_args();
		$output = $args[0];
		for($i = 1; $i < $argc; $i++)
		{
			$splicethis = preg_replace("'\{([0-9]+)\}'", "&#x7B;\\1&#x7D;", $args[$i]);
			$output = str_replace("{".($i-1)."}", $splicethis, $output);
		}
		print $output;
	}
}

?>
