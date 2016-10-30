<?php
$query = Query("SELECT name FROM {users}");
$names = array();
while ($name = FetchRow($query))
{
	$name = strtolower(preg_replace('/[^a-zA-Z]/', '', $name[0]));
	// Name might not use any letters. Skip those.
	if ($name)
		$names[] = $name;
}
?>
<script>
$(function () {
	var names = <?php echo json_encode($names) ?>;
	$('#un').change(function () {
		var i, length, name = this.value.toLowerCase().replace(/[^a-zA-Z]/g, '');
		for (i = 0, length = names.length; i < length; i++) {
			if (names[i] === name) {
				$('#original').show('fast');
				return;
			}
		}
		$('#original').hide('fast');
	});
});
</script>
<table class="message margin" id="original" style="display:none">
	<tr class="header0"><th>Warning</th></tr>
	<tr class="cell0"><td>You should be more original with your user names.</td></tr>
</table>
<?php
require 'pages/register.php';
?>
