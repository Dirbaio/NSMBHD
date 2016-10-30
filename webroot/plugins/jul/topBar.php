<?php

if ($loguser['powerlevel'] > 3)
{
	$shitbugs = @file_get_contents('shitbugs.dat');
	$shitbugs = $shitbugs ? unserialize($shitbugs) : array();

	$extra = '';
	if (count($shitbugs) > 0)
		$extra = format(", last at <strong>{0}</strong> by <strong>{1} ({2})</strong>", formatdate($shitbugs[0]['date']), $shitbugs[0]['ip'], $shitbugs[0]['banflags']);

	write("
	<table class=\"outline margin width100\">
		<tr class=\"cell1\">
			<td style=\"text-align: center;\">
				{0}
			</td>
		</tr>
	</table>
", actionLinkTag(format("<span style=\"color: #f00; font-weight: normal;\"><strong>{0}</strong> suspicious request(s) logged{1}</a>", count($shitbugs), $extra), 'shitbugs'));

}

?>