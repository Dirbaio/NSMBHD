<?php

$received = $user["postplusones"];
if($user["postplusones"])
	$received .= " [".actionLinkTag("View...", "listplusones", $user["id"])."]";

$res = query("select count(*) as ct, u.(_userfields)
from postplusones l
left join posts p on l.post=p.id
left join users u on u.id = l.user
where p.user={0}
group by l.user
order by count(*) desc
limit 6", $user["id"]);

$plusoners = array();

while($row = fetch($res))
	if(count($plusoners) == 5)
		$plusoners[] = "more...";
	else
		$plusoners[] = userLink(getDataPrefix($row, "u_"))." (".$row["ct"].")";

if(count($plusoners))
	$received .= "<br/>".__("From:")." ".implode(", ", $plusoners);

$profileParts[__("General information")][__("Total +1s received")] = $received;




$given = $user["postplusonesgiven"];


$res = query("select count(*) as ct, u.(_userfields)
from postplusones l
left join posts p on l.post=p.id
left join users u on u.id = p.user
where l.user={0}
group by p.user
order by count(*) desc
limit 6", $user["id"]);

$plusoners = array();

while($row = fetch($res))
	if(count($plusoners) == 5)
		$plusoners[] = "more...";
	else
		$plusoners[] = userLink(getDataPrefix($row, "u_"))." (".$row["ct"].")";

if(count($plusoners))
	$given .= "<br/>".__("To:")." ".implode(", ", $plusoners);

$profileParts[__("General information")][__("Total +1s given")] = $given;

