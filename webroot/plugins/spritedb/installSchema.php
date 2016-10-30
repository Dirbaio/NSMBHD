<?php

$tables["sprites"] = array
	(
		"fields" => array
		(
			"id" => $genericInt,
			"name" => $var256,
			"known" => $bool,
			"complete" => $bool,
			"orig" => $bool,
			"notes" => $text,
			"files" => $text,
			"classid" => $genericInt,
			"lasteditor" => $genericInt,
			"category" => $genericInt,
			"revision" => $genericInt,
			"date" => $genericInt,
			"fields" => $text,
		),
		"special" => "key `idrev` (`id`, `revision`), key `revision` (`revision`), key `classid` (`classid`), key `category` (`category`)"
	);

$tables["spriterevisions"] = array
	(
		"fields" => array
		(
			"id" => $genericInt,
			"revision" => $genericInt,
			"locked" => $bool,
		),
		"special" => $keyID
	);


$tables["spritecategories"] = array
	(
		"fields" => array
		(
			"id" => $genericInt,
			"name" => $var256,
			"ord" => $genericInt,
		),
		"special" => $keyID
	);
