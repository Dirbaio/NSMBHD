<?php

$title = "Download NSMB Editor";

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("NSMB Editor Downloads"), "download"));
makeBreadcrumbs($crumbs);

$buildurl = "http://builds.nsmbhd.net/revs";

if($_GET["id"] == "all")
	include("allrevs.php");
else if($_GET["id"] == "")
	include("lastrev.php");
else
{
	$latest = Fetch(Query("select * from svnbuilds order by revision desc limit 1"));
	$latest = $latest["revision"];

	$rev = $_GET["id"];
	if($rev == "latest")
		$rev = $latest;

	$code = doHash($_SERVER["REMOTE_ADDR"]."LOLfoahcmpughapw9hgcapuhcgn".$rev);
	$url = actionLink("getdownload", $rev, "c=$code");

	$regurl = actionLink("register");
	print "
	<table class=\"outline margin\" style=\"width: 60%; margin: auto;  margin-top: 30px; margin-bottom:30px;\">
		<tr class=\"header0\">
			<th colspan=\"6\">NSMB Editor Downloads</th>
		</tr>
		<tr>
			<td class=\"cell0\" colspan=\"6\" style=\"text-align:center; padding:18px;\">
				$adsense<br>
				<h3>Thanks for downloading NSMB Editor.</h3>
				Your download should start shortly.	If it doesn't, click <a href=\"$url\">here</a>.
				<iframe style=\"visibility:hidden;display:none\" width=\"1\" height=\"1\" frameborder=\"0\" src=\"$url\"></iframe>
				<br><br><br>
				<h3>Now, register at the forums!</h3>
				Join the forums now to participate in the NSMB Hacking Domain community! You will find:
				<div style=\"margin-left:200px; text-align:left\">
					<ul>
						<li>Tutorials.
						<li>Help with using the editor.
						<li>A place to download NSMB hacks.
						<li>...and share your own hack too!
						<li>And much more!
					</ul>
				</div>
		<a rel=\"nofollow\" class='cell1' style='font-size:17px; border: 1px solid black; padding: 5px; margin: 5px; display:block;' href='$regurl'>&raquo; Click HERE to register! &laquo;</a>
			</td>
		</tr>
	</table>";
}
