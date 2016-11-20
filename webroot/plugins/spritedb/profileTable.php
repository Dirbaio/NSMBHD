<?php
	
	$numedits = FetchResult("SELECT COUNT(*) FROM {sprites} WHERE lasteditor={0}", $user['id']);
	$numcontribs = FetchResult("SELECT COUNT(DISTINCT id) FROM {sprites} WHERE lasteditor={0}", $user['id']);
	$lastedits = Query("SELECT id, name, date FROM {sprites} WHERE lasteditor={0} ORDER BY date DESC LIMIT 10", $user['id']);
	
	$profileParts['Sprite database']['Total edits'] = $numedits;
	$profileParts['Sprite database']['Sprites edited'] = $numcontribs;
	
	$edits = array();
	while ($edit = Fetch($lastedits))
		$edits[] = actionLinkTag($edit['name'].' ('.$edit['id'].')', 'spritedb', '', 'go='.$edit['id']).' on '.formatdate($edit['date']);
	
	if (count($edits))
		$profileParts['Sprite database']['Last edits'] = implode('<br />', $edits);
	
?>
