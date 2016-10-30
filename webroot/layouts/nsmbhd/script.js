"use strict"

function doScroll() {
	var navigation = $("#navigation");
	var userpanel = $("#userpanel");
	var userpanelPH = $("#userpanel-placeholder");

	var view = $(window);
	var viewTop = view.scrollTop();

	var sidebarTop = $("#sidebar").offset().top;
	var margin = 35;
	
	if ((viewTop > sidebarTop - margin) && !navigation.is(".navigation-fixed"))
		navigation.addClass("navigation-fixed");
	else if ((viewTop <= sidebarTop - margin) && navigation.is(".navigation-fixed"))
		navigation.removeClass("navigation-fixed");

	var userpanelTop = userpanelPH.offset().top;

	if ((viewTop > userpanelTop) && !userpanel.is(".userpanel-fixed"))
	{
		userpanelPH.height(userpanelPH.height());
		userpanelPH.width(userpanelPH.width());
		userpanel.addClass("userpanel-fixed");
	}
	else if ((viewTop <= userpanelTop) && userpanel.is(".userpanel-fixed"))
	{
		userpanelPH.css("height", "auto");
		userpanelPH.css("width", "auto");
		userpanel.removeClass("userpanel-fixed");
	}
}

$(function() {
	var view = $(window);
	view.bind("scroll resize", doScroll);
	doScroll();
});
 
