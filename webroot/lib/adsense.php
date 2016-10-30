<?php

function makeAdsense()
{
	global $mobileLayout, $loguserid, $loguser;
	if($mobileLayout) return;
	if($loguserid && $loguser["powerlevel"] >= 0) return;

	$loginText = actionLinkTag("Register", "register")." or ".actionLinkTag("login", "login")." to hide ads";
	if($loguser["powerlevel"] < 0)
		$loginText = "You're banned, so have some delicious ads.";
		
	echo "
		<table class=\"post margin\">
			<tr>
				<td class=\"side userlink\">
					<span class=\"nc00\" style=\"font-weight:bold;\">NSMBHD</span>
				</td>
				<td class=\"meta right\">
					<div style=\"float: left;\" id=\"meta_${post['id']}\">
						Sponsored ads
					</div>
					$loginText
				</td>
			</tr>
			<tr>
				<td class=\"side\">
					<div class=\"smallFonts\">
						<br><br>
						Posts: &infin;/&infin;<br>
						Since: Jun 26th 2011
					</div>
				</td>
				<td class=\"post\">
					<div>
						".getAdsenseCode()."
					</div>
				</td>
			</tr>
		</table>";
}

function getAdsenseCode()
{
	return '<script type="text/javascript"><!--
google_ad_client = "ca-pub-0261153085306189";
/* NSMBHD thread view */
google_ad_slot = "3695558755";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
}
