<?php

$tables["svnbuilds"] = array
	(
		"fields" => array
		(
			"revision" => $genericInt,
			"message" => $text,
			"date" => $genericInt,
			"status" => $genericInt,
			"gitrevision" => $var256,
			"gitbranch" => $var256,
		),
		"special" => "key `revision` (`revision`)"
	);

