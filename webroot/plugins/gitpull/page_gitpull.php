<?php
if($loguser['powerlevel'] < 2)
	Kill(__("You're not admin. There is nothing for you here."));

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Admin"), "admin"));
$crumbs->add(new PipeMenuLinkEntry(__("Update board"), "gitpull"));
makeBreadcrumbs($crumbs);

// Running "git pull" straight off the GET meant any page an admin visited
// could deploy whatever the remote had. Confirm it with a token-checked POST.
if(!isset($_POST['action']))
{
	echo '
	<form method="post" action="'.actionLink("gitpull").'">
		<input type="hidden" name="key" value="'.htmlspecialchars($loguser['token']).'" />
		<table class="outline margin width50">
			<tr class="header0"><th>'.__("Update board").'</th></tr>
			<tr class="cell1"><td>'.__("This runs \"git pull\" on the board directory.").'</td></tr>
			<tr class="cell2"><td>
				<input type="submit" name="action" value="'.__("Update the board").'" />
			</td></tr>
		</table>
	</form>';
	return;
}

if(!isset($_POST['key']) || !hash_equals($loguser['token'], $_POST['key']))
	Kill(__("No."));

$output = array();
exec("git pull 2>&1", $output);
echo '<div style="width: 50%; margin-left: auto; margin-right: auto; background: black; border: 1px solid #0f0; color: #0f0; font-family: \'Consolas\', \'Lucida Console\', \'Courier New\', monospace;">';

if (empty($output)) echo '<em>(no output)</em>';
else
	foreach ($output as $line) echo htmlspecialchars($line).'<br>';

echo '</div>';

?>
