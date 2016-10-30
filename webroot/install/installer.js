"use strict";

String.prototype.endsWith = function(suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

var page = 0;
var numPages;

//0 = not installed
//1 = installing
//2 = installed
var installationState = 0;

function setStep(newPage) {
	if(page != newPage)
	{
		page = newPage;
		$("#progress").html("Step&nbsp;"+page+"&nbsp;of&nbsp;"+numPages);
		$("#progress").animate({width: ((page/ numPages) * 100)+"%"}, 200);
		$(".page").slideUp(200);
		$("#page"+page).slideDown(200);
		if(page == 3 && installationState == 0)
			startInstallation();
	}
	
	if(page == 2)
		$("#nextPageButton").html(upgrade?"Upgrade!":"Install!");
	else
		$("#nextPageButton").html("Next &rarr;");

	if (page == 1 || (page == 3 && installationState != 0))
		$("#prevPageButton").attr("disabled", "disabled");
	else
		$("#prevPageButton").removeAttr("disabled");

	if (page == numPages || (page == 3 && installationState != 2))
		$("#nextPageButton").attr("disabled", "disabled");
	else
		$("#nextPageButton").removeAttr("disabled");
	
}

function setState(newState)
{
	installationState = newState;
	setStep(page);
}

function startInstallation()
{
	$("#install-output").text(upgrade?"Upgrading...":"Installing...");
	setState(1);

	$.post("install/doinstall.php", {
		action: "install",
		dbserv: $('#sqlServerAddress').val(),
		dbuser: $('#sqlUserName').val(),
		dbpass: $('#sqlPassword').val(),
		dbname: $('#sqlDbName').val(),
		dbpref: $('#sqlTablePrefix').val(),
		convert: $('#convert').is(':checked'),
		convertFrom: $('#convertFrom').val(),
		convertDbName: $('#convertDbName').val(),
		convertDbPrefix: $('#convertDbPrefix').val(),
	}, function(data) {
		if(data.trim().endsWith("Success!"))
			setState(2);
		else
		{
			data += "\n\nAn error occured. Please go back and fix the settings and try again.\n";
			setState(0);
		}
		$("#install-output").html(data);
	});
}

$(function() {
	if(upgrade)
		$('.install-only').hide();
	else
		$('.upgrade-only').hide();
	if(sqlConfigured)
		$('.sql-not-configured').hide();
	else
		$('.sql-configured').hide();


	numPages = $("#installPager div.page").length;
	$('.page').hide();
	$('#installUI').fadeIn(100);
	$('#progress').css("width", "0%");
	setStep(1);
	$("#convert").click(function() {
		$("#convertToggle").slideToggle();
	});
	$("#prevPageButton").click(function() {
		if (page > 1)
			setStep(page-1);
	});
	$("#nextPageButton").click(function() {
		if (page < numPages)
			setStep(page+1);
	});
});

