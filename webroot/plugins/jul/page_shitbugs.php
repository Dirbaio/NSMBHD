<?php

$title = "Admin Cruft";

if ($loguser['powerlevel'] < 4)
	Kill('No.');

$shitbugs = @file_get_contents('shitbugs.dat');
$shitbugs = $shitbugs ? unserialize($shitbugs) : array();

echo "
	<table class=\"outline margin width100\">
		<tr class=\"header0\">
			<th>
				Date
			</th>
			<th>
				IP
			</th>
			<th>
				Matching users
			</th>
			<th>
				&nbsp;
			</th>
		</tr>
";

foreach ($shitbugs as $foo)
{
	$date = formatdate($foo['date']);

	$userlisting = '';
	$users = Query("SELECT name FROM {users} WHERE lastip={0} ORDER BY name", $foo['ip']);
	while ($user = Fetch($users))
		$userlisting .= htmlspecialchars($user['name']).', ';

	if (!$userlisting) $userlisting = 'None';
	else $userlisting = substr($userlisting, 0, strlen($userlisting)-2);

	echo "
		<tr class=\"cell0\">
			<td>
				{$date}
			</td>
			<td>
				".formatIP($foo['ip'])."
			</td>
			<td>
				{$userlisting}
			</td>
		</tr>
";
}

echo "
	</table>
";

?>
