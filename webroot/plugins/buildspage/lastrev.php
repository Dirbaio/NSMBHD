<?php

$latestrev = Fetch(Query("select * from svnbuilds order by revision desc limit 1"));
$latest = $latestrev["revision"];

$url = actionLink("download", "latest");

print "
<table class=\"outline margin\" style=\"width: 60%; margin: auto; text-align:center; margin-top: 30px; margin-bottom:30px;\">
	<tr class=\"header0\">
		<th>Latest build</th>
	</tr>
	<tr class=\"cell0\">
		<td style='padding:20px;'>
			Here you can download the latest builds of NSMB Editor.<br>
			These are built automatically from <a href=\"https://github.com/Dirbaio/NSMB-Editor\">the GitHub repository</a>.<br><br>
		<div style='font-size:17px; text-align:center; width:100%;'>NSMB Editor 5.2 latest version: Build $latest<br>
		<a rel=\"nofollow\" class='cell1' style='border: 1px solid black; padding: 5px; margin: 5px; display:block;' href='$url'>&raquo; Download NSMB Editor b$latest &laquo;</a>
		</div>
		<br>
		This is the latest build, which is supposed to be the best one! If you don't know what to download, download this one.<br>
		If you want, you can check out ".actionLinkTag("previous versions", "download", "all").".<br>
		</td>
	</tr>
</table>
";
write('</td><td style="border: 0px none; vertical-align: top; padding-right: 1em; padding-bottom: 1em;">');
