<?php

$ajaxPage = true;

$theme = $_GET['id'];

$themeFile = "themes/$theme/style.css";
if(!file_exists($themeFile))
	$themeFile = "themes/$theme/style.php";


checkForImage($layout_logopic, true, "logos/logo_$theme.png");
checkForImage($layout_logopic, true, "logos/logo_$theme.jpg");
checkForImage($layout_logopic, true, "logos/logo_$theme.gif");
checkForImage($layout_logopic, true, "logos/logo.png");
checkForImage($layout_logopic, true, "logos/logo.jpg");
checkForImage($layout_logopic, true, "logos/logo.gif");
checkForImage($layout_logopic, false, "themes/$theme/logo.png");
checkForImage($layout_logopic, false, "themes/$theme/logo.jpg");
checkForImage($layout_logopic, false, "themes/$theme/logo.gif");
checkForImage($layout_logopic, false, "themes/$theme/logo.png");
checkForImage($layout_logopic, false, "img/logo.png");

$result = array(
	"css" => $themeFile,
	"logo" => $layout_logopic,
);

echo json_encode($result);

/*
function checkForImage(&$image, $external, $file)
{
	global $dataDir, $dataUrl;

	if($image) return;

	if($external)
	{
		if(file_exists($dataDir.$file))
			$image = $dataUrl.$file;
	}
	else
	{
		if(file_exists($file))
			$image = resourceLink($file);
	}
}
*/
