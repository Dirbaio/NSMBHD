<?php

$title = __("Uploader");

AssertForbidden("viewUploader");

if($uploaderWhitelist)
	$goodfiles = explode(" ", Settings::pluginGet('uploaderWhitelist'));

$badfiles = array("html", "htm", "php", "php2", "php3", "php4", "php5", "php6", "htaccess", "htpasswd", "mht", "js", "asp", "aspx", "cgi", "py", "exe", "com", "bat", "pif", "cmd", "lnk", "wsh", "vbs", "vbe", "jse", "wsf", "msc", "pl", "rb", "shtm", "shtml", "stm", "htc");

if(isset($_POST['action']))
	$_GET['action'] = $_POST['action'];
if(isset($_POST['fid']))
	$_GET['fid'] = $_POST['fid'];

$quota = Settings::pluginGet('uploaderCap') * 1024 * 1024;
$pQuota = Settings::pluginGet('personalCap') * 1024 * 1024;

$maxSizeMult = Settings::pluginGet('uploaderMaxFileSize') * 1024 * 1024;

if($_GET['action'] == "uploadform")
{

	$cat = getCategory($_GET["cat"]);
	if (!is_numeric($_GET["cat"]))
		Kill('Invalid category');

	$cat = getCategory($_GET["cat"]);

	$crumbs = new PipeMenu();
	$crumbs->add(new PipeMenuLinkEntry(__("Uploader"), "uploader"));
	$crumbs->add(new PipeMenuLinkEntry($cat["name"], "uploaderlist", "", "cat=".$cat["id"]));
	$crumbs->add(new PipeMenuTextEntry(__("Upload file")));
	makeBreadcrumbs($crumbs);

	if($loguserid && IsAllowed("useUploader"))
	{
		print format(
		"
		<script type=\"text/javascript\">
			window.addEventListener(\"load\", function() { hookUploadCheck(\"newfile\", 1, {1}) }, false);
		</script>
		<form action=\"".actionLink("uploader")."\" method=\"post\" enctype=\"multipart/form-data\">
			<input type='hidden' name='cat' value='${_GET["cat"]}'>
			<table class=\"outline margin\">
				<tr class=\"header0\">
					<th colspan=\"4\">".__("Upload")."</th>
				</tr>
				<tr class=\"cell0\">
					<td>File</td><td>
						<input type=\"file\" id=\"newfile\" name=\"newfile\" style=\"width: 80%;\" />
					</td>
				</tr>
				<tr class=\"cell1\">
					<td>Description</td><td>
						<input type=\"text\" name=\"description\" style=\"width: 80%;\" />
					</td>
				</tr>
				<tr class=\"cell0\">
					<td></td><td>
						<input type=\"submit\" id=\"submit\" name=\"action\" value=\"".__("Upload")."\" disabled=\"disabled\" />
					</td>
				</tr>
				<tr class=\"cell1 smallFonts\">
					<td colspan=\"3\">
						".__("The maximum upload size is {0} per file. You can upload the following types: {2}.")."
						<div id=\"sizeWarning\" style=\"display: none; font-weight: bold\">".__("File is too large.")."</div>
						<div id=\"typeWarning\" style=\"display: none; font-weight: bold\">".__("File is not an allowed type.")."</div>
					</td>
				</tr>
			</table>
		</form>
		", BytesToSize($maxSizeMult), $maxSizeMult, Settings::pluginGet('uploaderWhitelist'));

	}
}

else if($_GET['action'] == __("Upload"))
{
	AssertForbidden("useUploader");
	if($loguserid)
	{
		$cat = getCategory($_POST["cat"]);
		$file = $_FILES['newfile'];
		$privateFlag = 0;
		if($_POST['cat'] == -1)
			$privateFlag = 1;
		var_dump($file);
		if($file['size'] == 0)
		{
			if($file['tmp_name'] == "")
				Alert(__("No file given."));
			else
				Alert(__("File is empty."));
		}
		else if($file['size'] > Settings::pluginGet('uploaderMaxFileSize') * 1024 * 1024)
		{
			Alert(format(__("File is too large. Maximum size is {0}."), BytesToSize(Settings::pluginGet('uploaderMaxFileSize') * 1024 * 1024)));
		}
		else
		{
			$extension = strtolower(end(explode(".", $file['name'])));

			if(in_array(strtolower($extension), $badfiles) || is_array($goodfiles) && !in_array(strtolower($extension), $goodfiles))
			{
				Alert(__("Forbidden file type."));
			}
			else
			{
				$id = Shake();
				$hash = md5_file($file['tmp_name']);

				Query("insert into {files} (id, name, hash, date, user) values ({0}, {1}, {2}, {3}, {4})",
					$id, $file['name'], $hash, time(), $loguserid);

				Query("insert into {uploader} (id, description, private, category) values ({0}, {1}, {2}, {3})",
					$id, $_POST['description'], $privateFlag, $_POST['cat']);

				$dir = $dataDir."uploads/".substr($hash, 0, 2).'/';
				mkdir($dir);
				copy($file['tmp_name'], $dir.$hash);
				//Report("[b]".$loguser['name']."[/] uploaded file \"[b]".$fname."[/]\"".($privateFlag ? " (privately)" : ""), $privateFlag);

				die(header("Location: ".actionLink("uploaderlist", "", "cat=".$_POST["cat"])));
			}
		}
	}
	else
		Alert(__("You must be logged in to upload."));
}
else if($loguserid && $_GET['action'] == "multidel" && $_POST['del']) //several files
{
	$deleted = 0;
	foreach($_POST['del'] as $fid => $on)
	{
		if($loguser['powerlevel'] > 2)
			$check = FetchResult("select count(*) from {uploader} where id = {0}", $fid);
		else
			$check = FetchResult("select count(*) from {uploader} u join {files} f on u.id=f.id where f.user = {0} and u.id = {1}", $loguserid, $fid);

		if($check)
		{
			Query("delete from {uploader} where id = {0}", $fid);
			$deleted++;
		}
	}
	die(header("Location: ".actionLink("uploaderlist", "", "cat=".$_GET["cat"])));
}
else if($loguserid && $_GET['action'] == "multimove" && $_POST['del']) //several files
{

	$moved = 0;
	$newcat = $_POST['destcat'];
	if (!is_numeric($newcat))
		Kill('Invalid category ID');

	foreach($_POST['del'] as $fid => $on)
	{
		if($loguser['powerlevel'] > 2)
			$check = FetchResult("select count(*) from {uploader} where id = {0}", $fid);
		else
			$check = FetchResult("select count(*) from {uploader} u join {files} f on u.id=f.id where f.user = {0} and u.id = {1}", $loguserid, $fid);

		if($check)
		{
			if(!$entry['private'])
			{
				Query("update {uploader} set category = {0} where id = {1}", $newcat, $fid);
				$moved++;
			}
		}
	}
	die(header("Location: ".actionLink("uploaderlist", "", "cat=".$_GET["cat"])));
}

else if($_GET['action'] == "delete") //single file
{
	$fid = (int)$_GET['fid'];

	if($loguser['powerlevel'] > 2)
		$check = FetchResult("select count(*) from {uploader} where id = {0}", $fid);
	else
		$check = FetchResult("select count(*) from {uploader} u join {files} f on u.id=f.id where f.user = {0} and u.id = {1}", $loguserid, $fid);

	if($check)
	{
		Query("delete from {uploader} where id = {0}", $fid);
		die(header("Location: ".actionLink("uploaderlist", "", "cat=".$_GET["cat"])));
	}
	else
		Alert(__("No such file or not yours to mess with."));
}
else
{
	$crumbs = new PipeMenu();
	$crumbs->add(new PipeMenuLinkEntry(__("Uploader"), "uploader"));
	makeBreadcrumbs($crumbs);

	$errormsg = __("No categories found.");
	$entries = Query("select * from {uploader_categories} order by ord");

	if(NumRows($entries) == 0)
	{
		print "
		<table class=\"outline margin\">
			<tr class=\"header0\">



				<th colspan=\"7\">".__("Files")."</th>
			</tr>
			<tr class=\"cell1\">
				<td colspan=\"4\">
					".$errormsg."
				</td>
			</tr>
		</table>
		";
	}
	else
	{
		print
		"
		<table class=\"outline margin width50\">
			<tr class=\"header0\">
				<th colspan=\"7\">".__("Categories")."</th>
			</tr>
		";

		$cellClass = 0;

		while($entry = Fetch($entries))
		{
			$filecount = FetchResult("select count(*) from {uploader} where category = {0}", $entry['id']);

			print "<tr class=\"cell$cellClass\"><td>";
			print actionLinkTag($entry['name'], "uploaderlist", "", "cat=".$entry['id']);
			print "<br>";
			print $entry['description'];
			print "<br>";
			print $filecount." files.";
			print "<br>";
			print "</td></tr>";
			$cellClass = ($cellClass+1) % 2;
		}

		if($loguserid)
		{
			$filecount = FetchResult("select count(*) from {uploader} u join {files} f on u.id=f.id where f.user = {0} and u.private = 1", $loguserid);

			print "<tr class=\"cell$cellClass\"><td>";
			print actionLinkTag("Private files", "uploaderlist", "", "cat=-1");
			print "<br>";
			print "Only for you.";
			print "<br>";
			print $filecount." files.";
			print "<br>";
			print "</td></tr>";

			$cellClass = ($cellClass+1) % 2;

			if($loguser['powerlevel'] > 2)
			{
				$filecount = FetchResult("select count(*) from {uploader} where uploader.private = 1");

				print "<tr class=\"cell$cellClass\"><td>";
				print actionLinkTag("All private files", "uploaderlist", "", "cat=-2");
				print "<br>";
				print $filecount." files.";
				print "<br>";
				print "</td></tr>";
			}
		}
		print "</table>";
	}
}

function getCategory($cat)
{
	if (!is_numeric($cat))
		Kill('Invalid category');

	if($cat >= 0)
	{
		$rCategory = Query("select * from {uploader_categories} where id={0}", $cat);
		if(NumRows($rCategory) == 0) Kill("Invalid category");
		$rcat = Fetch($rCategory);
	}
	else if($cat == -1)
		$rcat = array("id" => -1, "name" => "Private files");
	else if($cat == -2)
		$rcat = array("id" => -2, "name" => "All private files");
	else
		Kill('Invalid category');

	return $rcat;
}

?>
