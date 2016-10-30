<?php

$revs = Query("select * from svnbuilds order by revision desc limit 30");

print 	"
	<tr class=\"header1\">
		<th></th>
		<th>Build</th>
		<th>Size</th>
		<th>Date</th>
		<th>Branch</th>
		<th>Revision</th>
	</tr>
";

while($rev = Fetch($revs))
{
	$revnum = $rev["revision"];
	$zipurl = actionLink("download", $revnum);
	$zipfile = $dataDir."builds/nsmb-editor-$revnum.zip";

	$link = "<a rel=\"nofollow\" href=\"$zipurl\">NSMB Editor Build b$revnum</a>";
	$size = @BytesToSize(filesize($zipfile));

	$date = formatdate($rev["date"]);
	$branch = $rev["gitbranch"];
	$revis = $rev["gitrevision"];
	$revshort = substr($rev["gitrevision"], 0, 8);
	$class++;
	$class %= 2;
	print "<TR class=\"cell$class\"><td style='text-align:center;'>-</td><TD>$link</td>";
	print "<td>$size</td>";
	print "<td>$date</td>";
	print "<td>$branch</td>";
	print "<td><a href=\"https://github.com/Dirbaio/NSMB-Editor/commit/$revis\">$revshort</a></td>";
	print "</TR>\n";
}


print "</table>";
