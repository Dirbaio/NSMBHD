<?php
include("functions.php");

$mydatefmt = 'm-d-Y';
if ($loguserid) $mydatefmt = $loguser['dateformat'];

$title = 'Last spritedb changes';

if (isset($_GET['id']) && (int)$_GET['rev'] > 0)
{
	$rev = (int)$_GET['rev'];
	$maxrev = FetchResult("SELECT revision FROM {spriterevisions} WHERE id={0}", $_GET['id']);
	if ($maxrev < 0) Kill("Unknown sprite ID.");
	if ($maxrev < $rev) Kill("Revision requested is above the current revision.");
	
	$revs = Query("
		SELECT 
			s.*,
			sc.name catname,
			le.(_userfields)
		FROM 
			{sprites} s
			LEFT JOIN {spritecategories} sc ON sc.id=s.category
			LEFT JOIN {users} le ON le.id=s.lasteditor
		WHERE s.id={0} AND s.revision<={1}
		ORDER BY s.revision DESC LIMIT 2", $_GET['id'], $rev);
	$current = Fetch($revs);
	$previous = Fetch($revs);
	
	$crumbs = new PipeMenu();
	$crumbs->add(new PipeMenuLinkEntry(__("Sprite database"), "spritedb"));
	$crumbs->add(new PipeMenuLinkEntry(__("Last changes"), "spritedbchanges"));
	$crumbs->add(new PipeMenuLinkEntry('Changes for '.htmlspecialchars($current['name']), 'spritedbchanges', $current['id'], 'rev='.$current['revision']));
	makeBreadcrumbs($crumbs);
		
	function objInfo($obj)
	{
		$user = getDataPrefix($obj, "le_");
		
		if ($obj['revision'] > 0)
			$userinfo = 'By '.userLink($user).' on '.formatdate($obj['date']);
		else
			$userinfo = 'Starting revision';
			
		$flags = 'Category \''.htmlspecialchars($obj['catname']).'\'';
		$flags .= $obj['known'] ? ', known' : ', unknown';
		$flags .= ($obj['known'] && $obj['complete']) ? ', fully documented' : '';
		
		if ($obj['notes']) 
			$notes = nl2br(htmlspecialchars($obj['notes']));
		else
			$notes = '<small>(no description)</small>';
		
		$datafiles = trim($obj['files']);
		if ($datafiles) $datafiles = '<br> &bull; '.str_replace("\n", "<br> &bull; ", htmlspecialchars($datafiles));
		else $datafiles = ' none';
		
		$fields = $obj['fields'] ? unserialize($obj['fields']) : array();
		if(count($fields) != 0)
		{
			foreach($fields as $field)
				$objfields .= '<br> &bull; '.describefield($field);
		}
		else $objfields = ' none';
		
		return '
				<table class="outline margin">
					<tr class="header1"><th>'.htmlspecialchars($obj['name']).' ('.htmlspecialchars($obj['id']).', rev. '.$obj['revision'].')</th></tr>
					<tr class="cell1"><td>'.$userinfo.'</td></tr>
					<tr class="cell2"><td>'.$flags.'</td></tr>
					<tr class="cell1"><td>'.$notes.'</td></tr>
					<tr class="cell2"><td>Data files:'.$datafiles.'</td></tr>
					<tr class="cell1"><td>Sprite fields:'.$objfields.'</td></tr>
				</table>';
	}
	
	$revs = Query("SELECT s.revision FROM {sprites} s LEFT JOIN {spriterevisions} sr ON sr.id=s.id WHERE s.id={0} AND s.revision>0 AND s.revision<=sr.revision ORDER BY s.revision ASC", $_GET['id']);
	$revList = '';
	while ($therev = Fetch($revs))
	{
		if ($therev[0] == $rev)
			$revList .= '&nbsp;'.$therev[0].'&nbsp; ';
		else
			$revList .= '&nbsp;'.actionLinkTag($therev[0], 'spritedbchanges', $current['id'], 'rev='.$therev[0]).'&nbsp; ';
	}
	
	echo
"
	<div class=\"smallFonts margin\">
		View changes for revision: {$revList}
	</div>
";
		
	echo '
	<table class="width100">
		<tr>
			<td style="width:45%; vertical-align:top;">
				'.objInfo($previous).'
			</td>
			<td style="font-size:100px; text-align:center; font-weight:bold; vertical-align:middle;">
				&#x21E8;
			</td>
			<td style="width:45%; vertical-align:top;">
				'.objInfo($current).'
			</td>
		</tr>
	</table>';
	
	return;
}

	
$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Sprite database"), "spritedb"));
$crumbs->add(new PipeMenuLinkEntry(__("Last changes"), "spritedbchanges"));
makeBreadcrumbs($crumbs);

$time = (int)$_GET['time'];
if (!$time) $time = 86400;

$spans = array(86400=>'Today', 604800=>'This week', 2592000=>'This month');
$spanList = "";
foreach($spans as $span=>$text)
{
	if ($span == $time)
		$spanList .= '<li>'.$text.'</li>';
	else
		$spanList .= actionLinkTagItem($text, 'spritedbchanges', '', 'time='.$span);
}
echo
"
	<div class=\"smallFonts margin\">
		View changes for:
		<ul class=\"pipemenu\">
			{$spanList}
		</ul>
	</div>
";

$mindate = time() - $time;
$changes = Query("
	SELECT 
		s.*,
		le.(_userfields)
	FROM 
		{spriterevisions} sr 
		LEFT JOIN {sprites} s ON s.id=sr.id AND s.revision<=sr.revision
		LEFT JOIN {users} le ON le.id=s.lasteditor
	WHERE s.revision>0 AND s.date>{0}
	ORDER BY s.date DESC", $mindate);
	
echo '
	<table class="outline margin">
		<tr class="header1">
			<th>Sprite</th>
			<th>Edited by</th>
			<th>On</th>
			<th>Revision</th>
		</tr>';
		
$today = cdate($mydatefmt, time());
$yesterday = cdate($mydatefmt, time()-86400);
$lastts = 'lol';
$c = 1;
$ulcache = array();

while ($change = Fetch($changes))
{
	$date = $change['date'];
	$ts = cdate($mydatefmt, $date);
	if ($ts == $today) $ts = 'Today';
	else if ($ts == $yesterday) $ts = 'Yesterday';
	
	if ($ts != $lastts)
	{
		$lastts = $ts;
		echo '
		<tr class="header0">
			<th colspan="4">'.$ts.'</th>
		</tr>';
	}
	
	$user = getDataPrefix($change, "le_");
	$userlink = userLink($user);
	
	$complink = ' ('.actionLinkTag('changes', 'spritedbchanges', $change['id'], 'rev='.$change['revision']).')';
	
	echo '
		<tr class="cell',$c,'">
			<td>',actionLinkTag($change['name'], 'spritedb', '', 'go='.$change['id']),' (Sprite ',htmlspecialchars($change['id']),')</td>
			<td class="center">',$userlink,'</td>
			<td class="center">',formatdate($change['date']),'</td>
			<td class="center">',$change['revision'],$complink,'</td>
		</tr>';
		
	$c = ($c==1)?2:1;
}

echo '
	</table>';

?>
