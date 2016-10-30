<?php
//  AcmlmBoard XD - Board Settings editing page
//  Access: administrators

$title = __("Edit settings");

AssertForbidden("editSettings");

if($loguser['powerlevel'] < 3)
	Kill(__("You must be an administrator to edit the board settings."));

$plugin = "main";
if(isset($_GET["id"]))
	$plugin = $_GET["id"];
if(isset($_POST["_plugin"]))
	$plugin = $_POST["_plugin"];

if(!ctype_alnum($plugin))
	Kill(__("No."));

$crumbs = new PipeMenu();
$crumbs->add(new PipeMenuLinkEntry(__("Admin"), "admin"));
if($plugin == "main")
	$crumbs->add(new PipeMenuLinkEntry(__("Edit settings"), "editsettings"));
else
{
	$crumbs->add(new PipeMenuLinkEntry(__("Plugin manager"), "pluginmanager"));
	$crumbs->add(new PipeMenuLinkEntry($plugins[$plugin]["name"], "editsettings", $plugin));
}
makeBreadcrumbs($crumbs);

$settings = Settings::getSettingsFile($plugin);
$oursettings = Settings::$settingsArray[$plugin];
$invalidsettings = array();

if(isset($_POST["_plugin"]))
{
	//Save the settings.
	$valid = true;

	foreach($_POST as $key => $value)
	{
		if($key == "_plugin") continue;

		//Don't accept unexisting settings.
		if(!isset($settings[$key])) continue;

		//Save the entered settings for re-editing
		$oursettings[$key] = $value;

		if(!Settings::validate($value, $settings[$key]["type"], $settings[$key]["options"]))
		{
			$valid = false;
			$invalidsettings[$key] = true;
		}
		else
			Settings::$settingsArray[$plugin][$key] = $value;
	}

	if($valid)
	{
		Settings::save($plugin);
		if($plugin == "main")
			logAction('editsettings', array());
		else
			logAction('editplugsettings', array('text' => $plugin));

		if(isset($_POST["_exit"]))
		{
			if($plugin == "main")
				redirectAction("admin");
			else
				redirectAction("pluginmanager");
		}
		else
			Alert(__("Settings were successfully saved!"));
	}
	else
		Alert(__("Settings were NOT saved because there were invalid values. Please correct them and try again."));
}

$plugintext = "";
if($plugin != "main")
	$plugintext = " for plugin ".$plugin;
print "
	<form action=\"".actionLink("editsettings")."\" method=\"post\">
		<input type=\"hidden\" name=\"_plugin\" value=\"$plugin\">
		<table class=\"outline margin width75\">

			<tr class=\"header1\">
				<th colspan=\"2\">
					".__("Settings")."$plugintext
				</th>
			</tr>";

$class = 0;

foreach($settings as $name => $data)
{
	$friendlyname = $name;
	if(isset($data["name"]))
		$friendlyname = $data["name"];

	$type = $data["type"];
	$help = $data["help"];
	$options = $data["options"];
	$value = $oursettings[$name];

	$input = "[Bad setting type]";

	$value = htmlspecialchars($value);

	if($type == "boolean")
		$input = makeSelect($name, $value, array(1=>"Yes", 0=>"No"));
	if($type == "options")
		$input = makeSelect($name, $value, $options);
	if($type == "integer" || $type == "float")
		$input = "<input type=\"text\" id=\"$name\" name=\"$name\" value=\"$value\" />";
	if($type == "text")
		$input = "<input type=\"text\" id=\"$name\" name=\"$name\" value=\"$value\" class=\"width75\"/>";
	if($type == "password")
		$input = "<input type=\"password\" id=\"$name\" name=\"$name\" value=\"$value\" class=\"width75\"/>";
	if($type == "textbox" || $type == "textbbcode" || $type == "texthtml")
		$input = "<textarea id=\"$name\" name=\"$name\" rows=\"8\" style=\"width: 98%;\">$value</textarea>";
	if($type == "forum")
		$input = makeForumList($name, $value);
	if($type == "theme")
		$input = makeThemeList($name, $value);
	if($type == "layout")
		$input = makeLayoutList($name, $value);
	if($type == "language")
		$input = makeLangList($name, $value);

	$invalidicon = "";
	if($invalidsettings[$name])
		$invalidicon = "[INVALID]";

	if($help)
		$help = "<img src=\"".resourceLink("img/icons/icon4.png")."\" title=\"$help\" alt=\"[!]\" />";

	print "<tr class=\"cell$class\">
				<td>
					<label for=\"$name\">$friendlyname</label>
				</td>
				<td>
					$input
					$help
					$invalidicon
				</td>
			</tr>";
	$class = ($class+1)%2;
}

print "			<tr class=\"cell2\">
				<td>
				</td>
				<td>
					<input type=\"submit\" name=\"_exit\" value=\"".__("Save and Exit")."\" />
					<input type=\"submit\" name=\"_action\" value=\"".__("Save")."\" />
					<input type=\"hidden\" name=\"key\" value=\"{31}\" />
				</td>
			</tr>
		</table>
	</form>
";

function makeSelect($fieldName, $checkedIndex, $choicesList, $extras = "")
{
	$checks[$checkedIndex] = " selected=\"selected\"";
	foreach($choicesList as $key=>$val)
		$options .= format("
						<option value=\"{0}\"{1}>{2}</option>", $key, $checks[$key], $val);
	$result = format(
"
					<select id=\"{0}\" name=\"{0}\" size=\"1\" {1} >{2}
					</select>", $fieldName, $extras, $options);
	return $result;
}

function prepare($text)
{
	$s = str_replace("\\'", "'", addslashes($text));
	return $s;
}


function makeThemeList($fieldname, $value)
{
	$themes = array();
	$dir = @opendir("themes");
	while ($file = readdir($dir))
	{
		if ($file != "." && $file != "..")
		{
			$name = explode("\n", @file_get_contents("./themes/".$file."/themeinfo.txt"));
			$themes[$file] = trim($name[0]);
		}
	}
	closedir($dir);
	return makeSelect($fieldname, $value, $themes);
}

function makeLayoutList($fieldname, $value)
{
	$layouts = array();
	$dir = @opendir("layouts");
	while ($layout = readdir($dir))
		if($layout != "." && $layout != "..")
			$layouts[$layout] = @file_get_contents("./layouts/".$layout."/info.txt");

	closedir($dir);
	return makeSelect($fieldname, $value, $layouts);
}

function makeLangList($fieldname, $value)
{
	$data = array();
	$dir = @opendir("lib/lang");
	while ($file = readdir($dir))
	{
		//print $file;
		if (endsWith($file, "_lang.php"))
		{
			$file = substr($file, 0, strlen($file)-9);
			$data[$file] = $file;
		}
	}
	$data["en_US"] = "en_US";
	closedir($dir);
	return makeSelect($fieldname, $value, $data);
}

//From the PHP Manual User Comments
function foldersize($path)
{
	$total_size = 0;
	$files = scandir($path);
	$files = array_slice($files, 2);
	foreach($files as $t)
	{
		if(is_dir($t))
		{
			//Recurse here
			$size = foldersize($path . "/" . $t);
			$total_size += $size;
		}
		else
		{
			$size = filesize($path . "/" . $t);
			$total_size += $size;
		}
	}
	return $total_size;
}

?>
