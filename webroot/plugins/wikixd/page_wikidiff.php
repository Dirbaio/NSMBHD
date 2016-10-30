<?php

require 'wikilib.php';
require 'Diff.php';
require 'Diff/Renderer/inline.php';

?>
<style type="text/css">
#wikidiff ins, #wikidiff del { text-decoration: none; }
#wikidiff ins { background: #060; color: #cfc; }
#wikidiff del { background: #600; color: #fcc; }
</style>
<?php

$rev = (int)$_GET['rev'];
$page = getWikiPage($_GET['id'], $rev);
$rev = min($page['revision'], $rev);

$urltitle = $page['id'];//urlencode($page['id']);
$nicetitle = htmlspecialchars(url2title($page['id']));
$title = 'Wiki &raquo; Diff: '.$nicetitle;

$links = new PipeMenu();

//I think this is broken. 
$links -> add(new PipeMenuLinkEntry('Page', 'wiki', substr($urltitle,5)));
$links -> add(new PipeMenuLinkEntry('Discuss', 'wiki', 'Talk:'.$urltitle));

if ($page['canedit'])
	$links -> add(new PipeMenuLinkEntry('Edit', 'wikiedit', $urltitle));
	
makeLinks($links);


$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Wiki"), "wiki"));
if ($page['ismain'])
	$crumbs->add(new PipeMenuLinkEntry('Main page: Diff', 'wikidiff', $urltitle, 'rev='.$rev));
else
{
	$crumbs->add(new PipeMenuLinkEntry($nicetitle, 'wiki', $urltitle));
	$crumbs->add(new PipeMenuLinkEntry('Diff', 'wikidiff', $urltitle, 'rev='.$rev));
}
makeBreadcrumbs($crumbs);
	
if ($page['new']) Kill('This page has not been created yet.');
if ($page['revision'] <= 1) Kill('This page has not been edited since its creation.');
if ($page['flags'] & WIKI_PFLAG_DELETED) Kill('This page has been deleted.');

$previous = Fetch(Query("SELECT revision, text FROM {wiki_pages_text} WHERE id={0} AND revision<{1} ORDER BY revision DESC LIMIT 1", $urltitle, $rev));
if (!$previous) Kill('Previous revision missing.');

echo '
		<table class="outline margin" id="wikidiff">
			<tr class="cell1">
				<td style="padding:0px 1em 1em;">';
	
$revInfo = '';
$revList = '';

if ($rev > 0) 
{
	$revs = Query("SELECT pt.revision r FROM {wiki_pages_text} pt WHERE pt.id={0} AND pt.revision>1 ORDER BY r ASC", $urltitle);
	while ($therev = Fetch($revs))
	{
		if ($therev['r'] == $rev)
			$revList .= '&nbsp;'.$therev['r'].'&nbsp;';
		else
			$revList .= '&nbsp;'.actionLinkTag($therev['r'], 'wikidiff', $urltitle, 'rev='.$therev['r']).'&nbsp;';
	}
	
	$revInfo = 'Viewing diff between revisions '.$previous['revision'].' (previous) and '.$rev.' (current)<br>(revisions: &nbsp;1&nbsp;'.$revList.')<br><br>';
}

echo '<h1>'.$nicetitle.'</h1>'.$revInfo.dodiff($page['text'], $previous['text']);

echo '
				</td>
			</tr>
		</table>';
		
		
function dodiff($cur, $prev)
{
	$cur = str_replace("\r", '', $cur);
	$prev = str_replace("\r", '', $prev);
	
	$diff = new Text_Diff('native', array(explode("\n",$prev), explode("\n",$cur)));
	$renderer = new Text_Diff_Renderer_inline();
	
	$stuff = nl2br($renderer->render($diff));
	return '<div style="font-family:\'Consolas\',\'Courier New\',monospace; border:1px dashed #ccc; padding: 1em;">'.$stuff.'</div>';
}

?>

