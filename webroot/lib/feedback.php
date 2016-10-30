<?php
//  AcmlmBoard XD support - System feedback

function Debug($s)
{
	write("<strong>Debug</strong>: {0}<br />", $s);
}

//	Not really much different to kill()
function Alert($s, $t="")
{
	if($t=="")
		$t = __("Alert");

	print '<table class="message outline margin">
		<tr class="header0"><th>'.$t.'</th></tr>
		<tr class="cell0"><td>'.$s.'</td></tr>
	</table>';
}

function Kill($s, $t="")
{
	if($t=="")
		$t = __("Error");
	Alert($s, $t);
	throw new KillException();
}

function dieAjax($what)
{
	global $ajaxPage;

	echo $what;
	$ajaxPage = true;
	throw new KillException();
}

?>
