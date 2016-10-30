//===================================
// Functions for Niko's Forum Editor

var fid = 0;
var hint = true;

function geteditforaurl()
{
	if((document.location+"").indexOf("?") == -1) 
		return document.location + "?action=";
	else
		return document.location + "&action=";
}

function loadEditForum()
{
	$("#editcontent").load(geteditforaurl()+'editforum&fid='+fid);
}

function pickForum(id) {
	if (hint == true) {
		$("#hint").remove();
		hint = false;
	}
	$(".f, .c").removeClass("fe_selected");
	$("#forum"+id).addClass("fe_selected");
	if ($("#editcontent").is(":hidden")) $("#editcontent").show();
	fid = id;
	loadEditForum();
}

function pickCategory(id) {
	if (hint == true) {
		$("#hint").remove();
		hint = false;
	}
	$(".f, .c").removeClass("fe_selected");
	$("#cat"+id).addClass("fe_selected");
	if ($("#editcontent").is(":hidden")) $("#editcontent").show();
	$("#editcontent").load(geteditforaurl()+'editcategory&cid='+id);
	fid = id;
}

function changeForumInfo()
{
	var postdata = $("#forumform").serialize();
	$.post(geteditforaurl()+"updateforum", postdata, function(data) {
		data = $.trim(data);
		if(data == "Ok")
		{
			$("#flist").load(geteditforaurl()+"forumtable");
			$("#editcontent").html("");
		}
		else
			alert("Error: "+data);
	});
}


function changeCategoryInfo()
{
	var postdata = $("#forumform").serialize();
	$.post(geteditforaurl()+"updatecategory", postdata, function(data) {
		data = $.trim(data);
		if(data == "Ok")
		{
			$("#flist").load(geteditforaurl()+"forumtable");
			$("#editcontent").html("");
		}
		else
			alert("Error: "+data);
	});
}

function addForum()
{
	var postdata = $("#forumform").serialize();

	$.post(geteditforaurl()+"addforum", postdata, function(data) {
		data = $.trim(data);
		if(data == "Ok")
		{
			$("#flist").load(geteditforaurl()+"forumtable");
			$("#editcontent").html("");
		}
		else
			alert("Error: "+data);
	});
}

function addCategory()
{
	var postdata = $("#forumform").serialize();

	$.post(geteditforaurl()+"addcategory", postdata, function(data) {
		data = $.trim(data);
		if(data == "Ok")
		{
			$("#flist").load(geteditforaurl()+"forumtable");
			$("#editcontent").html("");
		}
		else
			alert("Error: "+data);
	});
}

function deleteForum(what)
{
	var postdata = $("#deleteform").serialize();
/*	var msg = "sent to hell.";

	if(what == "delete")
		msg = "DELETED COMPLETELY!";
	if(what == "trash")
		msg = "CLOSED AND TRASHED!";
	if(what == "move")
		msg = "moved to the forum you selected.";
	if(what == "leave")
		msg = "left in the database as-is. This is NOT RECOMMENDED and will probably cause problems! \n\nFor example, the threads and posts will still count towards user\'s postcounts but will be invisible";

	if(!confirm("Are you sure that you want to delete the forum?\nThreads in the forum will be "+msg))
		return;
	if(!confirm("Are you COMPLETELY SURE? This is your last opportunity to cancel"))
		return;*/

	if(!confirm("Are you sure that you want to delete the forum?"))
		return;

	$.post(geteditforaurl()+"deleteforum", postdata, function(data) {
		data = $.trim(data);
		if(data == "Ok")
		{
			$("#flist").load(geteditforaurl()+"forumtable");
			$("#editcontent").html("");
		}
		else
			alert("Error: "+data);
	});
}


function deleteCategory(what)
{
	var postdata = $("#deleteform").serialize();

	if(!confirm("Are you sure that you want to delete the category?"))
		return;

	$.post(geteditforaurl()+"deletecategory", postdata, function(data) {
		data = $.trim(data);
		if(data == "Ok")
		{
			$("#flist").load(geteditforaurl()+"forumtable");
			$("#editcontent").html("");
		}
		else
			alert("Error: "+data);
	});
}

function newForum()
{
	$('#editcontent').load(geteditforaurl()+'editforumnew');
}

function newCategory()
{
	$('#editcontent').load(geteditforaurl()+'editcategorynew');
}

function showDeleteForum()
{
	$("#deleteforum").slideDown("slow");
}

function hideDeleteForum()
{
	$("#deleteforum").slideUp("slow");
}

function deleteMod(mid)
{
	$.get(geteditforaurl()+'deletemod&mid='+mid+'&fid='+fid, function(data) {
		data = $.trim(data);
		if(data == "Ok")
			loadEditForum();
		else
			alert("Error: "+data);
	});
}

function addMod(mid)
{
	var mid = $("#addmod").val();
	$.get(geteditforaurl()+'addmod&mid='+mid+'&fid='+fid, function(data) {
		data = $.trim(data);
		if(data == "Ok")
			loadEditForum();
		else
			alert("Error: "+data);
	});
}
