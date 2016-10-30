"use strict";

//LINKS AND STUFF

//The value of this is set by PHP
var boardroot = "";

function resourceLink(url)
{
	return boardroot + url;
}


//AJAX STUFF

function ajax(page, params, callback)
{
	params["page"] = page;
	$.get(boardroot, params, callback);
}

function ajaxPost(page, params, callback)
{
	$.post(boardroot+"?page="+page, params, callback);
}


//AJAX REFRESHERS

//Value of this is set by PHP too.
//var onlineFID = 0;
var refreshUrl = "";
var onlineFID = 0;

function startAjaxRefresh()
{
	//onlineFID = fid;
	//setTimeout("getOnlineUsers()", 10000);
	//var onlineUsersBar = $('.header0').get(1);
	//onlineUsersBar.id="onlineUsersBar";
	var tmrid = window.setInterval(ajaxRefresh, 10000);

	$(window).blur(function() {
		if (tmrid != -9999)
		{
			window.clearInterval(tmrid);
			tmrid = -9999;
		}
	});

	$(window).focus(function() {
		ajaxRefresh();
		if (tmrid == -9999)
			tmrid = window.setInterval(ajaxRefresh, 10000);
	});
}

function ajaxRefresh()
{
	ajax("getviewcount", {}, function(data) {
	    $("#viewCount").html(data);
	});
	
	ajax("getonlineusers", {id: onlineFID}, function(data) {
	    $("#onlineUsers").html(data);
	});
	
	//FIXME: This sucks
	if(refreshUrl != "")
		$.get(refreshUrl, "", function(data) {
			$("#page_contents").html(data);
		});
}


//SPOILER BUTTONS

function toggleSpoiler()
{
	var button = this.parentNode.children[0];
	var div = this.parentNode.children[1];

	if(div.className == "spoiled")
	{
		if(button.className != "spoilerbutton named")
			button.textContent = "Show spoiler";
		div.className = "spoiled hidden";
	}
	else
	{
		if(button.className != "spoilerbutton named")
			button.textContent = "Hide spoiler";
		div.className = "spoiled";
	}
}

//Expandables
function expandSmilies()
{
	var button = document.getElementById("smiliesExpand");
	var expandedSet = $("#expandedSet");
	if(expandedSet.is(":hidden"))
	{
		expandedSet.slideDown(200, function()
		{
			button.textContent = String.fromCharCode(0x25B2);
		});
	}
	else
	{
		expandedSet.slideUp(200, function()
		{
			button.textContent = String.fromCharCode(0x25BC);
		});
	}
}

function expandPostHelp()
{
	var button = document.getElementById("postHelpExpand");
	var expandedSet = $("#expandedHelp");

	if(expandedSet.is(":hidden"))
	{
		expandedSet.slideDown(700, function()
		{
			button.textContent = String.fromCharCode(0x25B2);
		});
	}
	else
	{
		expandedSet.slideUp(700, function()
		{
			button.textContent = String.fromCharCode(0x25BC);
		});
	}
}

//EDIT PROFILE TABS

function showEditProfilePart(newId)
{
	var tables = document.getElementsByClassName('eptable');
	for (var i=0;i<tables.length;i++) {
		tables[i].style.display = "none";
	}
	document.getElementById(newId).style.display = "table";
	var tabs = document.getElementsByClassName('tab');
	for (var i=0;i<tabs.length;i++) {
		tabs[i].className = "tab";
	}
	document.getElementById(newId+"Button").className = "tab selected";
}

//POST CONTROLS 

var textEditor;
function hookUpControls() {
	//Now functional!
	textEditor = document.getElementById("text");
	textEditor.addEventListener("keypress", HandleKey, true);
	ConstructToolbar();
}

function ConstructToolbar()
{
	var toolbar = document.createElement("DIV");
	toolbar.className = "postToolbar";

	var buttons =
	[
		{ icon: "bold", title: "Bold", insert: 'b'},
		{ icon: "italic", title: "Italic", insert: "i" },
		{ icon: "underline", title: "Underlined", insert: "u" },
		{ icon: "strikethrough", title: "Strikethrough", insert: "s" },
		{ separator: true },
		{ icon: "superscript", title: "Superscript", insert: "sup", html: true },
		{ icon: "subscript", title: "Subscript", insert: "sub", html: true },
		//{ icon: "A", title: "Big", insert: "big", html: true },
		//{ icon: "a", title: "Small", insert: "small", html: true },
		{ separator: true },
		{ icon: "link", title: "Link", insert: "url" },
		{ icon: "picture", title: "Image", insert: "img" },
		{ separator: true },
		{ icon: "quote-left", title: "Quote", insert: "quote" },
		{ icon: "ellipsis-horizontal", title: "Spoiler", style: "opacity: 0.25", insert: "spoiler" },
		//{ icon: "abc", title: "Insert code block", style: "font-family: monospace", insert: "code" },

	];

	for(var i = 0; i < buttons.length; i++) {
		var button = buttons[i];
		if (button.separator !== undefined && button.separator == true) {
			toolbar.appendChild(document.createTextNode(" "));
			continue;
		}

		var newButton = document.createElement("button");
		newButton.type = "button";

		if (button.title != undefined) {
			newButton.title = button.title;
		}

		if (button.callback !== undefined) {
			newButton.addEventListener("click", button.callback, false);
		} else {
			//Kind of a hack… -Nina
			newButton.insert = button.insert;
			newButton.insertHtml = button.html;
			newButton.addEventListener('click', function(e) {
				e.preventDefault();
				insert(this.insert, this.insertHtml);
			}, false);
		}

		var icon = document.createElement("i");
		icon.className = "icon-" + button.icon;

		newButton.appendChild(icon);

		toolbar.appendChild(newButton);
	}

	textEditor.parentNode.insertBefore(toolbar, textEditor);
}

function HandleKey() {
	if(event.ctrlKey && !event.altKey) {
		var charCode = event.charCode ? event.charCode : event.keyCode;
		var c = String.fromCharCode(charCode).toLowerCase();
		if (c == "b" || c == "i" || c == "u")
		{
			textEditor.focus();
			insert(c);
			event.preventDefault();
			return false;
		}
	}
}

function insert(stuff, html) {
	var oldSelS = textEditor.selectionStart;
	var oldSelE = textEditor.selectionEnd;
	var scroll = textEditor.scrollTop;
	var selectedText = textEditor.value.substr(oldSelS, oldSelE - oldSelS);

	if(html)
		textEditor.value = textEditor.value.substr(0, oldSelS) + "<" + stuff + ">" + selectedText + "</" + stuff + ">" + textEditor.value.substr(oldSelE);
	else
		textEditor.value = textEditor.value.substr(0, oldSelS) + "[" + stuff + "]" + selectedText + "[/" + stuff + "]" + textEditor.value.substr(oldSelE);

	textEditor.selectionStart = oldSelS + stuff.length + 2;
	textEditor.selectionEnd = oldSelS + stuff.length + 2 + selectedText.length;
	textEditor.scrollTop = scroll;
	textEditor.focus();
}



/* Quote support
   -------------
   Thanks to Mega-Mario for the idea
 */
function insertQuote(pid)
{
	ajax("getquote", {id: pid}, function(data)
	{
		var editor = $("#text")[0]; //we want the HTMLTextElement kthx
		editor.focus();
		if (document.selection)
			document.selection.createRange().text += data;
		else
			editor.value = editor.value.substring(0, editor.selectionEnd) + data + editor.value.substring(editor.selectionEnd, editor.value.length);
		editor.scrollTop = editor.scrollHeight;
	});
}

function insertChanLink(pid)
{
	var editor = document.getElementById("text");
	var linkText = ">>" + pid + "\r\n";
	editor.focus();
	if (document.selection)
		document.selection.createRange().text += linkText;
	else
		editor.value = editor.value.substring(0, editor. selectionEnd) + linkText + editor.value.substring(editor.selectionEnd, editor.value.length);
	editor.scrollTop = editor.scrollHeight;
}




/* Smiley tricks
   -------------
   Inspired by Mega-Mario's quote system.
 */
function insertSmiley(smileyCode)
{
	var editor = document.getElementById("text");
	editor.focus();
	if (document.selection)
	{
		document.selection.createRange().text += " " + smileyCode;
	}
	else
	{
		editor.value = editor.value.substring(0, editor. selectionEnd) + smileyCode + editor.value.substring(editor.selectionEnd, editor.value.length);
	}
	editor.scrollTop = editor.scrollHeight;
}



// Live theme changer by Mega-Mario
function ChangeTheme(newtheme)
{
	ajax("getthemefiles", {id: newtheme}, function(data)
	{
		var stuff = JSON.parse(data);
		$("#theme_css")[0].href = stuff.css;
		$("#theme_banner")[0].src = stuff.logo;
	});
}



//Search page pager
function ChangePage(newpage)
{
        var pagenums = document.getElementsByClassName('pagenum');
        for (i = 0; i < pagenums.length; i++)
                pagenums[i].href = '#';

        pagenums = document.getElementsByClassName('pagenum'+newpage);
        for (i = 0; i < pagenums.length; i++)
                pagenums[i].removeAttribute('href');

        var pages = document.getElementsByClassName('respage');
        for (i = 0; i < pages.length; i++)
                pages[i].style.display = 'none';

        pages = document.getElementsByClassName('respage'+newpage);
        for (i = 0; i < pages.length; i++)
                pages[i].style.display = '';
}


//Stuff...?

function hideTricks(pid)
{
	$("#dyna_"+pid).hide(200);
	$("#meta_"+pid).show(200);
}

function showRevisions(pid)
{
	$("#meta_"+pid).hide(200);
	ajax("getrevisionlist", {id: pid}, function(data) {
		$("#dyna_"+pid).html(data);
		$("#dyna_"+pid).show(200);
	});
}

function showRevision(pid, rev)
{
	var post = $("#post_"+pid);
	ajax("getrevision", {id: pid, rev: rev}, function(data) {
		post.fadeOut(200, function()
		{
			post[0].innerHTML = data;
			post.fadeIn(200);
		});
	});
}

function deletePost(id, key, del)
{
	if(del == 1)
	{
		var reason = prompt('Enter a reason for deleting the post, or leave blank for no reason.');
		if (reason == null) return;
	}
	
	ajaxPost("deletepost", {id: id, key: key, reason: reason, delete: del}, function() {
		replacePost(id, false);
	});
}

function checkAll()
{
	var ca = document.getElementById("ca");
	var checked = ca.checked;
	var checks = document.getElementsByTagName("INPUT");
	for(var i = 0; i < checks.length; i++)
		checks[i].checked = checked;
}


function hookUploadCheck(id, type, size)
{
	var obj = document.getElementById(id);
	if(type == 0)
	{
		obj.onchange = function()
		{
			var submit = document.getElementById("submit");
			var sizeWarning = document.getElementById("sizeWarning");
			var typeWarning = document.getElementById("typeWarning");

			submit.disabled = (obj.value == "");

			if(obj.files != undefined)
			{
				var file = obj.files[0];
				var fileSize = 0;
				if(file != undefined)
					fileSize = file.size;
				sizeWarning.style['display'] = (fileSize > size) ? "inline" : "none";
				submit.disabled = (fileSize > size);
				if(file != undefined)
				{
					switch(file.type)
					{
						case "image/jpeg":
						case "image/png":
						case "image/gif":
							typeWarning.style['display'] = "none";
							break;
						default:
							typeWarning.style['display'] = "inline";
							submit.disabled = true;
					}
				}
			}
		};
	}
	else if(type == 1)
	{
		obj.onchange = function()
		{
			var submit = document.getElementById("submit");
			var sizeWarning = document.getElementById("sizeWarning");
			var typeWarning = document.getElementById("typeWarning");

			submit.disabled = (obj.value == "");
			if(obj.files != undefined)
			{
				var file = obj.files[0];
				var fileSize = 0;
				if(file != undefined)
					fileSize = file.size;
				sizeWarning.style['display'] = (fileSize > size) ? "inline" : "none";
				submit.disabled = (fileSize > size);
				if(file != undefined)
				{
					switch(file.type)
					{
						case "application/x-msdownload":
						case "text/html":
							typeWarning.style['display'] = "inline";
							submit.disabled = true;
							break;
						default:
							typeWarning.style['display'] = "none";
					}
				}
			}
		};
	}
}

function replacePost(id, opened)
{
	ajax("getpost", {id: id, o: opened?1:0}, function(data) {
		$("#post"+id).replaceWith(data);
	});
}

var themes;
function searchThemes(query) {
	if (themes === undefined) {
		themes = document.getElementsByClassName("theme");

		window.themeNames = {};

		for (var i = 0; i < themes.length; i++) {
			window.themeNames[themes[i].title] = i;
		}
	}

	var themeKeys = Object.keys(window.themeNames);
	query = query.toLowerCase();
	for (var i = 0; i < themes.length; i++) {
		if (query == "" || themeKeys[i].toLowerCase().indexOf(query) !== -1) {
			themes[i].style.display = "inline-block";
		} else {
			themes[i].style.display = "none";
		}
	}
}

$(document).ready(function() {
	$(".spoilerbutton").click(toggleSpoiler);
});

//EMAIL IN PROFILE
function loadEmail(id) {
	ajax("getemail", {id: id}, function(data) {
		$("#emailField").text(data);
	});
}

//MOBILE LAYOUT

function enableMobileLayout(val)
{
	setCookie("forcelayout", val, 20*365*24*60*60, "/");
	location.reload();
}

function setCookie(sKey, sValue, vEnd, sPath, sDomain, bSecure) {  
	if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/.test(sKey)) { return; }  
	var sExpires = "";  
	if (vEnd) {
		switch (typeof vEnd) {  
			case "number": sExpires = "; max-age=" + vEnd; break;  
			case "string": sExpires = "; expires=" + vEnd; break;  
			case "object": if (vEnd.hasOwnProperty("toGMTString")) { sExpires = "; expires=" + vEnd.toGMTString(); } break;  
		}  
	}  
	var lol = escape(sKey) + "=" + escape(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
	document.cookie = lol;
}
