<?php

function formatPlusOnes($plusones)
{
	$style = "";

	if($plusones >= 1)
	{
		$style .= "
			color:#3F0;
			font-size:20px;
			font-weight:bold;
			height:10px;
			float:right;
			";
		$style2 .= "
			padding:2px;
			background-color:rgba(0, 50, 0, 0.8);
			border: 1px solid #3F0;";
	}

	return "<span style=\"$style\"><span style=\"$style2\">+$plusones</span></span>";
}
