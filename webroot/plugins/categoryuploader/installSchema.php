<?php

$tables["uploader"] = array
	(
		"fields" => array
		(
			"id" => $var256,
			"description" => $var1024,
			"private" => $bool,
			"category" => $genericInt,
		),
		"special" => $keyID
	);

$tables["uploader_categories"] = array
	(
		"fields" => array
		(
			"id" => $AI,
			"name" => $var256,
			"description" => $text,
			"ord" => $genericInt,
		),
		"special" => $keyID
	);
