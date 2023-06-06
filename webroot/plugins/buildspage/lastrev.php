<?php

$latestrev = Fetch(Query("select * from svnbuilds order by revision desc limit 1"));
$latest = $latestrev["revision"];

$url = actionLink("download", "latest");

print "
<table class='outline margin' style='width: 60%; margin: auto; text-align:center; margin-top: 30px; margin-bottom:30px;'>
	<tr class='header0'>
		<th>Latest release</th>
	</tr>
	<tr class='cell0'>
		<td style='padding:20px;'>
			Here you can download the latest releases of NSMB Editor.<br>
			These link to the <a href='https://github.com/MammaMiaTeam/NSMB-Editor'>MammaMia Team fork repository</a>.<br><br>
			<div style='font-size:17px; text-align:center; width:100%;'>
				<a rel=\"nofollow\" class='cell1' style='border: 1px solid black; padding: 5px; margin: 5px; display:block;' href='https://github.com/MammaMiaTeam/NSMB-Editor/releases/latest/'>&raquo; Download NSMB Editor &laquo;</a>
			</div>
			<br>
			This is the latest release, which is probably what you need, so download this one if in doubt.<br><br>
			If you want, you can check out all the <a href='https://github.com/MammaMiaTeam/NSMB-Editor/releases'>other releases</a> or even the ".actionLinkTag("legacy builds", "download", "all").".<br><br>
			Note that the <a href='https://github.com/Dirbaio/NSMB-Editor'>original GitHub repository</a> (legacy) is no longer maintained.<br>
		</td>
	</tr>
</table>
";
write('</td><td style="border: 0px none; vertical-align: top; padding-right: 1em; padding-bottom: 1em;">');
