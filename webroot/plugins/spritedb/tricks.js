
var spriteRow = null;
var spriteRowID = -1;

function closeSprite()
{
	if(spriteRow != null)
	{
		var rowToDelete = spriteRow;
		$("div#spriteediting").slideUp("slow", function()
		{
		var idx = rowToDelete.rowIndex;
		rowToDelete.parentNode.deleteRow(idx);
		rowToDelete = null;
		});
		
		updateSprite(spriteRowID);
		spriteRow = null;
		spriteRowID = -1;
	}
}

function sendSpriteData(closeAfter)
{
	
	$("#spritefields tr").slice(4).each(function(i, tr) {
		$(tr).find("td input")[0].name = "title["+i+"]";
		$(tr).find("td input")[1].name = "nybble["+i+"]";
		$(tr).find("td select")[0].name = "type["+i+"]";
		$(tr).find("td input")[2].name = "data["+i+"]";
		$(tr).find("td input")[3].name = "comment["+i+"]";
	});


	var hasToCloseSprite = closeAfter;
	$("#savestatus").text(" Saving...");
	var postdata = $("#spritedataform").serialize();
	
	$.post("?page=spritedb&act=modsprite", postdata, function(data) {
		if(data.trim() == "Ok")
		{
			$("#savestatus").text(" Saved!");
			
//			alert(hasToCloseSprite);
			if(hasToCloseSprite == 1)
				closeSprite();
			else
				updateSprite(spriteRowID);			
		}
		else
		{
			$("#savestatus").text(" An error occured: "+data);
		}
 	});	
}

function updateSprite(spriteid)
{
	$.post("?page=spritedb&act=getsprite", {id:spriteid}, function(data) {
		$("#sprite"+spriteid).replaceWith(data);
	});	
}

function deleteField(thebutton)
{
	$(thebutton).parent().parent().detach();
}

function addField(spriteid)
{
	
	var newfield = "";
	newfield += "<tr class='cell0'>";
	newfield += "<td class='dragHandle'></td>";
	newfield += "<td><input type='text' name='title[x]' value=\"New Field\" size='10' class='text'></td>";
	newfield += "<td><input type='text' name='nybble[x]' value=\"1\" size='6' class='text'></td>";
	newfield += "<td><select name='type[x]'>";
	
	newfield += "<option value='checkbox'>checkbox</option>";
	newfield += "<option value='value' selected='selected'>value</option>";
	newfield += "<option value='signedvalue'>signedvalue</option>";
	newfield += "<option value='list'>list</option>";
	newfield += "<option value='binary'>binary</option>";
	newfield += "<option value='index'>index</option>";
	
	newfield +=  "</select></td>";
	newfield +=  "<td><input type='text' name='data[x]' value=\"\" size='35' class='text'></td>";
	newfield +=  "<td><input type='text' name='comment[x]' value=\"\" size='40' class='text'></td>";
	newfield +=  "<td style='font-size: 10px'><button type='button' onclick='deleteField(this); return false;'>Delete</button></td>";
	newfield +=  "</tr>";
	
	$("#spritefields").append(newfield);
	$("#spritefields").tableDnD({dragHandle: "dragHandle"});
}

function showsprite(elem, id)
{
	elem = elem.parentNode.parentNode;
	if(spriteRowID == id)
		closeSprite();
	else
	$.get("./", "page=spritedb&e="+id, function(data)
	{
		closeSprite();
		spriteRow = elem.parentNode.insertRow(elem.rowIndex+1);
		
		spriteRow.className = elem.className;
		spriteRow.innerHTML = "<td colspan='5'><div id='spriteediting' style='display:none;'>"+data+"</div></td>";
		spriteRowID = id;
		$("div#spriteediting").slideDown("slow");
		$("#spritefields").tableDnD({dragHandle: "dragHandle"});
	});
}

function showhidetypeinfo()
{
	$("div#typeinfo").slideToggle("slow");
}


