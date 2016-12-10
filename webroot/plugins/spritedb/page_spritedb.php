<?php

	include("functions.php");

	
	function printSpriteRow($row)
	{
		global $canEdit;
		
		if ($row['known'] == 0)
			$class = 'unknownp';
		else if ($row['complete'] == 0)
			$class = 'knownp';
		else
			$class = 'completep';
		
		print "<tr id='sprite${row['id']}' class='cell0'>";
		print "<td class=\"$class\" style=\"width:12px\"></td>";
		print "<td>${row['id']}<a name='${row['id']}'></a> </td>";
		print "<td>".htmlspecialchars($row['classid'])."</td>";
		if($canEdit)
			print "<td><a href=\"#\" onclick=\"showsprite(this, ${row['id']});return false;\">".htmlspecialchars($row['name']).'</a>';
		else
			print "<td><b>".htmlspecialchars($row['name']).'</b>';

		$fields = unserialize($row['fields']);
		if(count($fields) != 0)
		{
			print "<table style='width: 95%' class='data'>";
			foreach($fields as $field)
			{
				print "<tr><td>";
				print describefield($field);
				print "</td></tr>";
			}
			print "</table>";
		}

		print "</td>";
		
		$lastEditor = "-";
		if($row['lasteditor'] != 0)
			$lastEditor = UserLinkById($row['lasteditor']);
			
		print "<td>".$lastEditor." (rev. ".$row["revision"].')</td></tr>';
	}


	function printSpriteRowText($row)
	{
		global $wantGuest;

		print "Sprite ${row['id']}: ".htmlspecialchars($row['name'])."\n";

		$fields = unserialize($row['fields']);

		foreach($fields as $field)
		{
			print describefield($field, false);
			print "\n";
		}

		$lastEditor = "-";
		if($row['lasteditor'] != 0)
		{
			$qUser = "select * from users where id=".$row['lasteditor'];
			$rUser = Query($qUser);
			if(NumRows($rUser))
			{
				$user = Fetch($rUser);
				$lastEditor = $user["name"];
			}
			else
				$lastEditor = '??';
		}
			
		print "Last edited by ".$lastEditor."\n";
	}

	function printCatLink($id, $text)
	{
		global $game;
		if($_GET['cat'] == $id)
			print "${text}<br>";
		else
			print actionLinkTagUnescaped($text, "spritedb", "", ($id?"&cat=".$id:''))."<br>";
	}	
	

	//==============

	if (isset($_GET['e']))
	{
		$_GET['act'] = 'edit';
		$_GET['id'] = $_GET['e'];
	}

	$actions = array('list', 'edit', 'modsprite', 'addfield', 'deletefield', 'getsprite', 'spriteplaintext');
	
	$action = "";
	if(isset($_GET['act']))
		$action = $_GET['act'];
	
	if (!in_array($action, $actions))
		$action = 'list';
    

	if($action != 'list')
		$ajaxPage = TRUE;
	
	$title = "Sprite Database";

	$canEdit = $loguserid && ($loguser['powerlevel'] >= 0) && IsAllowed('editObjectDB');

    if($action != 'list' && $action != 'spriteplaintext' && ($wantGuest || $loguser['powerlevel'] < 0))
    	die("You can't do that!");
    
    switch ($action)
	{
		case 'list':
			$crumbs = new PipeMenu();
			$crumbs->add(new PipeMenuLinkEntry(__("Sprite database"), "spritedb"));
			makeBreadcrumbs($crumbs);

			$links = new PipeMenu();
			$links->add(new PipeMenuLinkEntry(__("Last changes"), "spritedbchanges"));
			$links->add(new PipeMenuHtmlEntry('<a href="/spritexml.php">Download database</a>'));
			makeLinks($links);

			if (!is_numeric($_GET["go"]))
				unset($_GET["go"]);
				
			if(isset($_GET["go"]))
			{
				$spid = intval($_GET["go"]);
				print "<script type='text/javascript'>setTimeout(\"showsprite(document.getElementById('sprite$spid'), $spid);\", 300);</script>";
			}
			
			print "<table class='outline margin'><tr class='header1'><th>Sprite DB</th><th>By Category</th></tr><tr class='cell0'>";
			print "<td>Welcome to the Sprite DB! Here you will find information on how to use any sprite in NSMB. <br>You can also have this database in NSMB Editor if you're using the latest version!<br><br>All the registered users can also collaborate with the Sprite DB by sharing their sprite data findings. Click any sprite below to edit it.";
			if (!$loguserid) print "<br><br>You need to be ".actionLinkTag('logged in', 'login')." to edit the sprite database.";
			else if ($loguser['powerlevel'] < 0) print "<br><br>Banned users may not edit the sprite database.";
			else if (!$canEdit) print "<br><br>You have been banned from editing the sprite database.";
			print "</td>";
			print "<td style='text-align:center;' rowspan='3'>";

			$entries = Query("select * from spritecategories order by ord, id");
			
			printCatLink(0, 'All sprites');
			print "<br>";
			printCatLink(-1, '<div class="outline margin unknownp" style="display:inline-block;height:10px;width:10px;vertical-align:baseline;margin-bottom:0px;"></div> Unknown');
			printCatLink(-2, '<div class="outline margin knownp" style="display:inline-block;height:10px;width:10px;vertical-align:baseline;margin-bottom:0px;"></div> Known, incomplete');
			printCatLink(-3, '<div class="outline margin completep" style="display:inline-block;height:10px;width:10px;vertical-align:baseline;margin-bottom:0px;"></div> Known, complete');
			print "<br>";
			while($entry = Fetch($entries))
				printCatLink($entry['id'], $entry['name']);

			$gg = "";
			if(isset($_GET["go"]))
				$gg = intval($_GET["go"]);
			if(isset($_POST["go"]) && $_POST["go"] != "")
				$gg = intval($_POST["go"]);
			print "<br><form action='".actionLink("spritedb")."' method='POST'>Go to sprite ID:<br><input type='text' maxlength='10' size='8' name='go' value='$gg'/><input type='submit' value='Go'/></form>";
			print "</td></tr><tr class='header1'><th>Status</th></tr><tr><td class='cell0'>";
			
			$gettotal = FetchRow(Query("select count(*) from {spriterevisions}"));
			$getoriginal = $gettotal;
			$getknown = FetchRow(Query("select count(*) from {spriterevisions} sr left join {sprites} s on s.id=sr.id and s.revision=sr.revision where s.known = 1"));// and orig = 0'));
			$getcomplete = FetchRow(Query("select count(*) from {spriterevisions} sr left join {sprites} s on s.id=sr.id and s.revision=sr.revision where s.complete = 1"));// and orig = 0'));

			$c = intval($getcomplete[0]);
			$o = $getoriginal[0];
			$k = $getknown[0] - $c;
			$u = $gettotal[0] - $k - $c;// - $o;
			
			print "&nbsp;{$gettotal[0]} total";
			if ($c > 0) print "<div class='percentbar completep' style='width: {$c}px'>$c</div>";
			if ($k > 0) print "<div class='percentbar knownp' style='width: {$k}px'>$k</div>";
			if ($u > 0) print "<div class='percentbar unknownp' style='width: {$u}px'>$u</div>";
			
			print "<br><br>The above bar needs <i>more green</i>. HELP US MAKE IT HAPPEN! NOW!";
			print "</td></tr></table>";
			
			
			$cond = "";
			if(isset($_GET["cat"]))
			{
				switch ($_GET['cat'])
				{
					case -1: $cond = "where s.known=0 and s.complete=0"; break;
					case -2: $cond = "where s.known=1 and s.complete=0"; break;
					case -3: $cond = "where s.known=1 and s.complete=1"; break;
					default: $cond = "where s.category={0}"; break;
				}
			}
			
			if($gg)
				$cond = "where s.id=".intval($gg);				

			$getsprites = Query("
				SELECT 
					s.*
				FROM 
					{spriterevisions} sr 
					LEFT JOIN {sprites} s ON s.id=sr.id AND s.revision=sr.revision
				{$cond}
				ORDER BY s.id", intval($_GET['cat']));

			$hasSprite = false;
			print "<table class='outline margin'>
				<tr class='header1'>
					<th style=\"width:12px\"></th>
					<th style='width: 60px'>ID</th>
					<th style='width: 60px'>Class ID</th>
					<th>Name</th>
					<th style='width: 150px'>Last edited by</th>
				</tr>";
			
			while ($row = Fetch($getsprites))
			{
				printSpriteRow($row);
				$hasSprite = true;
			}
			
			if(!$hasSprite)
				print "<tr class='cell0'><td colspan='5'>No sprites found</td></tr>";

			print "</table>";

			print '<div class="footer">
		Awesome sprite database PHP script created by Treeki. Adapted to NSMB DS and integrated into ABXD by Dirbaio.
		</div>';
			break;

		case 'edit':
			$id = $_GET['id'];
			if (!is_numeric($id))
				die('Invalid sprite ID');
			$id = intval($id);

			$sprite = getSprite($id);
			if(!$sprite)
				die("Can't find the sprite ID $id");
			
			print "<form id='spritedataform' onsubmit='sendSpriteData(1); return false;' action='".actionLink("spritedb", "", "act=modsprite")."' method='post'>";
			print "<input type='hidden' name='token' value='".$loguser["token"]."'>";
			print "<input type='hidden' name='id' value='$id'>";
			print "<table class='outline margin width50'>";
			print "<tr class='header1'><th>Sprite Information</th></tr>";

			$n = htmlspecialchars($sprite['name']);
			print "<tr class='cell0'><td>Name: <input type='text' name='spritename' value=\"{$n}\" class='text'></td></tr>";

			$entries = Query("select * from spritecategories order by ord, id");
			$catlist = "";
			
			while($entry = Fetch($entries))
			{
				$sel = "";
				if($sprite['category'] == $entry["id"])
					$sel = "selected='selected'";
					
				$catlist .= "<option value='${entry["id"]}' $sel>${entry["name"]}</option>";
			}
			$catlist = "<select name='cat' size='1'>$catlist</select>";

			print "<tr class='cell0'><td>Category: $catlist</td></tr>";

			$known = ($sprite['known'] == 1) ? " checked='checked'" : '';
			$complete = ($sprite['complete'] == 1) ? " checked='checked'" : '';

			print "<tr class='cell1'><td><input type='checkbox' name='known' value='yes'{$known}> This sprite's purpose is known</td></tr>";
			print "<tr class='cell0'><td><input type='checkbox' name='complete' value='yes'{$complete}> This sprite's data is complete</td></tr>";

			print "<tr class='cell1'><td><b>Notes:</b><br>";
			$notes = htmlspecialchars($sprite['notes']);
			print "<textarea name='notes' rows='4' cols='60' style='font-family: Arial,sans-serif'>$notes</textarea></td></tr>";
			
			print "<tr class='cell1'><td><b>Data Files:</b><br>List here all files the sprite uses, like graphics, textures or models.<br>Enter them one by line, like this: \"/obj/A_block_ncg.bin\"<br>";
			$files = htmlspecialchars($sprite['files']);
			print "<textarea name='files' rows='4' cols='60' style='font-family: Arial,sans-serif'>$files</textarea></td></tr>";
			print "<tr class='cell0'><td><center>";

			print "<button type='button' onclick='sendSpriteData(0); return false;'>Save</button>";
			print "<button type='button' onclick='sendSpriteData(1); return false;'>Save and Close</button>";
			print "<span id='savestatus'></span> </center></td></tr>";			print "</table>";

			print "<br>";

			// fields
			print "<table id='spritefields' class='outline margin width50'>";
			print "<tr class='header1 nodrop nodrag'><th colspan='7'>Sprite Fields</th></tr>";
?>
<tr class='cell1 nodrop nodrag'><td colspan='7'>

  <b>Field Types and Descriptions:</b> (<a href='' onclick='showhidetypeinfo(); return false;'>Show/Hide Info</a>)
  <div id='typeinfo' style='display: none'>
  <br>
  <i>About nybbles:</i>
Nybbles are hex digits. The 12 nybbles in the sprite data are numbered from left to right, starting from 0: 0-11.<br>
If you want the data to be just one nybble, enter its number.<br />
If you want it to be multiple nybbles, enter them first-last. For example, 2-3<br/><br>
  <b>Checkbox</b>: Activates/deactivates a specific bit in a nybble. Set the data to the value of the bit that will be activated.<br><br>
  <b>Value</b>: A simple value which shows up as a spinner in the editor. The data field is used as an added offset for the value.<br><br>
  <b>Signed value</b>: Same as a value, but it has a sign: can be positive or negative, using the two's complement.<br>
  <b>List</b>: Lets you choose from a list of values. Enter the values into the data field: <i>0=Right, 1=Up+Right, 2=Up, 3=Up+Left</i><br><br>
  <b>Binary</b>: Shows 4 checkboxes in a row, one for every bit in the nybble.</br>
  <b>Index</b>: Do not use it. It's use was for NSMBW stuff like the rotation indexes.<br><br>
  </div>
  </tr></td>
<?php
			$fields = unserialize($sprite['fields']);
			
			print "<tr class='cell1 nodrop nodrag'><td colspan='7'><center>";
			print "<button type='button'  onclick=\"addField($id); return false;\">Add field</button>";
			print "</center></td></tr>";
			print "<tr class='header1 nodrop nodrag'><th>Drag</th><th>Title</th><th>Nybble</th><th>Type</th><th>Options/Offset/Mask</th><th>Comment</th><th></th></tr>";

			$i = 0;
			foreach($fields as $field)
			{

	//0: Type
	//1: Nibbles
	//2: Value
	//3: Name
	//4: Notes
				$ftitle = htmlspecialchars($field[3]);
				$nybble = htmlspecialchars($field[1]);
				$type = $field[0];
				$data = htmlspecialchars(str_replace("\n", ', ', rtrim($field[2])), 0);
				$comment = htmlspecialchars($field[4]);
				print "<tr class='cell0'>";
				print "<td class='dragHandle'></td>";
				
				print "<td><input type='text' name='title[$i]' value=\"$ftitle\" size='10' class='text'></td>";
				print "<td><input type='text' name='nybble[$i]' value=\"$nybble\" size='6' class='text'></td>";
				print "<td><select name='type[$i]'>";
				foreach ($fieldtypes as $t)
				{
					print "<option value='$t'";
					if ($t == $type) print " selected='selected'";
						print ">$t</option>";
				}
				print "</select></td>";
				print "<td><input type='text' name='data[$i]' value=\"$data\" size='35' class='text'></td>";
				print "<td><input type='text' name='comment[$i]' value=\"$comment\" size='40' class='text'></td>";
				print "<td style='font-size: 10px'><button type='button' onclick='deleteField(this); return false;'>Delete</button></td>";
				print "</tr>";
				
				$i++;
			}
      		
      		
			print "</table>";
			print "</form>";

			print "<br>";

			print "<table class='outline margin width50'>";
			print "<tr class='header1'><th colspan='2'>Existing In-Game Sprite Data</th></tr>";
			
			$getdata = Query("select DISTINCT level, data from origdata where sprite = {0}", $id);
			if (NumRows($getdata) == 0)
			{
				print "<tr class='cell0'> <td>This sprite isn't used in the original game.</td></tr>";
			}
			else
			{
				$datavalues = array();
				print "<tr class='cell0'><td style='width: 240px'><b>Level</b></td><td><b>Data</b></td></tr>";
				while ($row = FetchRow($getdata))
				{
					if (!isset($datavalues[$row[0]])) $datavalues[$row[0]] = array();
						$datavalues[$row[0]][] = $row[1];
					//print "<tr><td>$row[1]</td><td>$row[2]</td></tr>";
				}
				$c = 1;
				foreach ($datavalues as $data => $levels)
				{
					print "<tr class='cell$c'><td valign='top'>$data</td><td>".implode('<br/>', $levels)."</td></tr>";
					$c++;
					if($c == 2) $c = 0;
				}
			}
			print "</table>";
			
			break;

			
		case 'modsprite':
			if(!$canEdit) die("You can't do that!");

			$id = $_POST['id'];
			if (!is_numeric($id))
				die('Invalid sprite ID');

			if($_POST['token'] != $loguser["token"]) 
				die("Bad token!");
			
			$id = intval($id);
			
			$sprite = getSprite($id);
			if (!$sprite)
				die("Can't find the sprite ID $id");

			//Now let's validate all the data!

			// SAVE FLAGS
			$known = 0; 	if ($_POST['known'] == 'yes') $known = 1;
			$complete = 0; 	if ($_POST['complete'] == 'yes') $complete = 1;


			// Sprite name
			$spritename = $_POST['spritename'];


			// Sprite category: it must exist
			$cat = intval($_POST['cat']);
			$getcategory = Query("select * from spritecategories where id = {0}", $cat);
			if (NumRows($getcategory) == 0)
				die("Can't find category ID $cat");


			// Notes and files.
			$notes = $_POST['notes'];
			$files = $_POST['files'];
			
			
			//Fields
			$fields = array();
			
			//Check if sprite is locked
			if($sprite["locked"] && $loguser["powerlevel"] < 1)
				die("This sprite is locked and can't be edited.");
			
			$usednybbles = array();
			if (isset($_POST['title']) && is_array($_POST['title']) && count($_POST['title']) > 0)
			{
				foreach ($_POST['title'] as $fid => $title)
				{
					$fieldtype = trim($_POST['type'][$fid]);
					$fieldnybble = trim($_POST['nybble'][$fid]);
					$fieldvalue = trim($_POST['data'][$fid]);
					$fieldname = trim($_POST['title'][$fid]);
					$fieldnotes = trim($_POST['comment'][$fid]);
					
					if("New Field" == $fieldname)
						die("Please no fields named \"New Field\"");

					if("" == $fieldname)
						die("Please no fields without title");
					
					if(!in_array($fieldtype, $fieldtypes))
						die("Invalid field type");
						
					$nybbles = explode("-", $fieldnybble);
					if(count($nybbles) != 1 && count($nybbles) != 2)
						die("Invalid nybble format (index count)");
						
					$nybblestart = $nybbles[0];
					$nybbleend = count($nybbles)==1?$nybbles[0]:$nybbles[1];
	
					if(!myisint($nybblestart)) die("Nybble index not a number");
					if(!myisint($nybbleend)) die("Nybble index not a number");

					if($nybblestart < 0 || $nybblestart > 11) die("Nybble index out of range");
					if($nybbleend < 0 || $nybbleend > 11) die("Nybble index out of range");

					for($i = $nybblestart; $i <= $nybbleend; $i++)
					{
						if($usednybbles[$i]) die("Two fields on the same nybble are not allowed");
						$usednybbles[$i] = true;
					}
					if($nybbleend < $nybblestart) die("Nybble range end is smaller than start");
					
					if($fieldtype == "list")
					{
						if(!preg_match('/^\d+=[^,]+(,\d+=[^,]+)*$/', $fieldvalue))
							die("You have entered an invalid list field value.");
					}
					else if($fieldvalue != "") 
						if(!myisint($fieldvalue)) die("Field value must be empty or an integer.");

					$fields[] = array($fieldtype, $fieldnybble, $fieldvalue, $fieldname, $fieldnotes);
				}
			}
			
			$fields = serialize($fields);
			
			$changed = false;
			if($spritename != $sprite["name"]) $changed = true;
			if($known != $sprite["known"]) $changed = true;
			if($complete != $sprite["complete"]) $changed = true;
			if($notes != $sprite["notes"]) $changed = true;
			if($files != $sprite["files"]) $changed = true;
			if($cat != $sprite["category"]) $changed = true;
			if($fields != $sprite["fields"]) $changed = true;
	
			if($changed)
			{
				$rev = $sprite['revision'] + 1;
				Query("INSERT INTO sprites (id,revision,name,known,complete,notes,lasteditor,date,files,category,fields,classid)
					VALUES ({0},{1},{2},{3},{4},{5},{6},{7},{8},{9},{10},{11})
					ON DUPLICATE KEY UPDATE name={2}, known={3}, complete={4}, notes={5}, lasteditor={6}, date={7}, files={8}, category={9}, fields={10}",
					$sprite['id'], $rev, $spritename, $known, $complete, $notes, $loguserid, time(), $files, $cat, $fields, $sprite["classid"]);
				Query("UPDATE spriterevisions SET revision={1} WHERE id={0}", $sprite['id'], $rev);
			}
			
			die("Ok");
			break;

		case 'getsprite':
		
			$id = $_POST['id'];
			$id = intval($id);
			if (!is_numeric($id))
				die('Invalid sprite ID');
			$sprite = getSprite($id);
			if(!$sprite)
				die("Can't find the sprite ID $id");
			
			printSpriteRow($sprite);
			
			die();
			break;
		case 'spriteplaintext':
		
			$id = $_GET['id'];
			$id = intval($id);
			if (!is_numeric($id))
				die('Invalid sprite ID');
			$sprite = getSprite($id);
			if(!$sprite)
				die("Can't find the sprite ID $id");

			printSpriteRowText($sprite);

			die();
			break;
	}

?>
