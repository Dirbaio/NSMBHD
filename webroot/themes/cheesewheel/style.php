<?php
header("Content-Type: text/css");

$curtime = getdate(time());
$min = $curtime['hours'] * 60 + $curtime['minutes'] + 340;

$hue = ($min / 2) % 360;
$sat = 50;
$hs = $hue.", ".$sat."%";

$css = "/* AcmlmBoard XD - Daily Cheese */
@import url('../../css/borders.css');

.faq
{
	border: 1px solid hsl([huesat], 5%);
	background: hsl([huesat], 11%);
}

#body
{
	background: #000;
	color: hsl([huesat], 75%);
}

.header0 th
{
	background: hsl([huesat], 20%);
	color: hsl([huesat], 75%);
}

.header1 th
{
	background: hsl([huesat], 25%);
	color: hsl([huesat], 75%);
}

.cell1, table.post td.post
{
	background: hsl([huesat], 11%);
}

.cell0, table.post td.side, table.post td.userlink, table.post td.meta
{
	background: hsl([huesat], 15%);
}

.cell2
{
	background: hsl([huesat], 8%);
}

table, td, th
{
	border-color: hsl([huesat], 5%);
}

button, input[type=\"submit\"]
{
	border: 1px solid hsl([huesat], 5%);
	background: hsl([huesat], 15%);
	color: hsl([huesat], 75%);
}

input[type=\"text\"], input[type=\"password\"], input[type=\"file\"], input[type=\"email\"], select, textarea
{
	background: hsl([huesat], 5%);
	border: 1px solid hsl([huesat], 25%);
	color: #fff;
}

input[type=\"checkbox\"], input[type=\"radio\"]
{
	background: hsl([huesat], 5%);
	border: 1px solid hsl([huesat], 25%);
	color: hsl([huesat], 50%);
}

div#tabs button.selected
{
	border-bottom: 1px solid hsl([huesat], 20%);
	background: hsl([huesat], 20%);
}

";

print str_replace("[huesat]", $hs, $css);

?>
