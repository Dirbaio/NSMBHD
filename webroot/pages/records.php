<?php
//  AcmlmBoard XD - The Records
//  Access: all


$title = __("Records");
AssertForbidden("viewRecords");
$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Records"), "records"));
makeBreadcrumbs($crumbs);

$df = "l, F jS Y, G:i:s";

$maxUsersText = $misc['maxuserstext'];
if($maxUsersText[0] == ":")
{
	$users = explode(":", $maxUsersText);

	$maxUsersText = "";
	foreach($users as $user)
	{
		if(!$user) continue;
		if($maxUsersText)
			$maxUsersText .= ", ";
		$maxUsersText .= UserLinkById($user);
	}
}

// Awesome way of calculating the mean birth date.
// I'm not sure if there's any problems with overflows and all. 
// But it seems to work fine :3

$sumAge = FetchResult("SELECT SUM(birthday) FROM {users} WHERE birthday != 0");
$countAge = FetchResult("SELECT COUNT(*) FROM {users} WHERE birthday != 0");
if ($countAge > 0)
{
	$avgAge = (int)($sumAge / $countAge);
	$avgAge = formatBirthday($avgAge);
}
else
	$avgAge = -1;

write(
"
<table class=\"outline margin width75\">
	<tr class=\"header0\">
		<th colspan=\"2\">
			".__("Highest Numbers")."
		</th>
	</tr>
	<tr class=\"cell0\">
		<td>
			".__("Highest number of posts in 24 hours")."
		</td>
		<td>
			".__("<strong>{0}</strong>, on {1} GMT")."
		</td>
	</tr>
	<tr class=\"cell1\">
		<td>
			".__("Highest number of posts in one hour")."
		</td>
		<td>
			".__("<strong>{2}</strong>, on {3} GMT")."
		</td>
	</tr>
	<tr class=\"cell0\">
		<td>
			".__("Highest number of users in five minutes")."
		</td>
		<td>
			".__("<strong>{4}</strong>, on {5} GMT")."
		</td>
	</tr>
	<tr class=\"cell1\">
		<td></td>
		<td>
			{6}
		</td>
	</tr>
	".(($avgAge > -1) ? "<tr class=\"cell0\">
		<td>
			".__("Average age of members")."
		</td>
		<td>
			".$avgAge."
		</td>
	</tr>":'')."
</table>
",	$misc['maxpostsday'], gmdate($df, $misc['maxpostsdaydate']),
	$misc['maxpostshour'], gmdate($df, $misc['maxpostshourdate']),
	$misc['maxusers'], gmdate($df, $misc['maxusersdate']),
	$maxUsersText);

$rStats = Query("show table status");
while($stat = Fetch($rStats))
	$tables[$stat['Name']] = $stat;

$tablelist = "";
$rows = $avg = $datlen = $idx = $datfree = 0;
foreach($tables as $table)
{
	$cellClass = ($cellClass+1) % 2;
	$tablelist .= format(
"
	<tr class=\"cell{0}\">
		<td class=\"cell2\">{1}</td>
		<td>
			{2}
		</td>
		<td>
			{3}
		</td>
		<td>
			{4}
		</td>
		<td>
			{5}
		</td>
		<td>
			{6}
		</td>
		<td>
			{7}
		</td>
	</tr>
",	$cellClass, $table['Name'], $table['Rows'], sp($table['Avg_row_length']),
	sp($table['Data_length']), sp($table['Index_length']), sp($table['Data_free']),
	sp($table['Data_length'] + $table['Index_length']));
	$rows += $table['Rows'];
	$avg += $table['Avg_row_length'];
	$datlen += $table['Data_length'];
	$idx += $table['Index_length'];
	$datfree += $table['Data_free'];
}

write(
"
<table class=\"outline margin\">
	<tr class=\"header0\">
		<th colspan=\"7\">
			".__("Table Status")."
		</th>
	</tr>
	<tr class=\"header1\">
		<th>
			".__("Name")."
		</th>
		<th>
			".__("Rows")."
		</th>
		<th>
			".__("Avg. data/row")."
		</th>
		<th>
			".__("Data size")."
		</th>
		<th>
			".__("Index size")."
		</th>
		<th>
			".__("Unused data")."
		</th>
		<th>
			".__("Total size")."
		</th>
	</tr>
	{0}
	<tr class=\"header1\">
		<th colspan=\"7\" style=\"height: 8px;\"></th>
	</tr>
	<tr class=\"cell2\">
		<td style=\"font-weight: bold;\">
			".__("Total")."
		</td>
		<td>
			{1}
		</td>
		<td>
			{2}
		</td>
		<td>
			{3}
		</td>
		<td>
			{4}
		</td>
		<td>
			{5}
		</td>
		<td>
			{6}
		</td>
	</tr>
</table>
", $tablelist, $rows, sp($avg), sp($datlen), sp($idx), sp($datfree), sp($datlen + $idx));

function sp($sz)
{
	return number_format($sz,0,'.',',');
}
?>
