<?php

$revs = Query("select * from svnbuilds order by revision desc limit 30");

print "
<table class=\"outline margin\" style=\"width: 60%; margin: auto;  margin-top: 30px; margin-bottom:30px;\">
	<tr class=\"header0\">
		<th colspan=\"6\">All Builds</th>
	</tr>
	<tr>
		<td class=\"cell0\" colspan=\"6\" style=\"text-align:center; padding:18px;\">
			These are all the NSMB Editor builds. <br>
			You might want to download the ".actionLinkTag("latest NSMB Editor version", "download"). " instead.
		</td>
	</tr>";
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
	$path = $dataDir."builds/nsmb-editor-$revnum.zip";
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
