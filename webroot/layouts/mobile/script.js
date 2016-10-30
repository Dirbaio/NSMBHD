"use strict";

var userAgent = window.navigator.userAgent;
var isSafari = userAgent.match(/iPad/i) || userAgent.match(/iPhone/i);

var hideTimeout = false;
function showDrawer(percent)
{
	$('#drawer-overlay').css('opacity', percent*0.75);

	if(hideTimeout)
	{
		clearTimeout(hideTimeout);
		hideTimeout = false;
	}
		
	if(percent == 0)
		hideTimeout = setTimeout(function() {
			$('#drawer-overlay').css('visibility', 'hidden');
			$('#drawer').css('visibility', 'hidden');
		}, 500);
	else
	{
		$('#drawer-overlay').css('visibility', 'visible');
		$('#drawer').css('visibility', 'visible');
	}
	
	var t = 300 * (percent-1);
	$("#drawer").css("-webkit-transform", "translateX("+t+"px)");
	$("#drawer").css("transform", "translateX("+t+"px)");
}

var drawerShown = false;

function resetDrawer()
{
	$('#drawer-overlay').css('opacity', "");
	$('#drawer-overlay').css('visibility', "");
	$("#drawer").css("-webkit-transform", "");
	$("#drawer").css("transform", "");
	$("#drawer").css("visibility", "");
		
	drawerShown = false;
}

var touchDown = false;
var touchDragging = false;
var touchDownX = 0;
var touchDownY = 0;
var touchDiffX = 0;
var touchTime = 0;

//Returns true if drawer should be always visible (not in mobile mode)
function drawerAlwaysVisible() {
	return window.innerWidth >= 768;
}

function startTouchDragging()
{
	touchDragging = true;
	$("#drawer").addClass("dragging");
	$("#drawer-overlay").addClass("dragging");
}

function stopTouchDragging()
{
	touchDragging = false;
	$("#drawer").removeClass("dragging");
	$("#drawer-overlay").removeClass("dragging");
}

$(document).ready(function() {
	$('#drawer-overlay').bind('click', function() {
		drawerShown = false;
		showDrawer(0.0);
	});
	$('#drawer-toggle').bind('click', function() {
		drawerShown = !drawerShown;
		if (drawerShown)
			showDrawer(1.0);
		else
			showDrawer(0.0);
		return false;
	});

	$(window).on('resize', function(){
		if(drawerAlwaysVisible())
			resetDrawer();
	});

	document.addEventListener('touchstart', function(event) {
		if(drawerAlwaysVisible()) return;
		// On Safari edge-swipe is used for back navigation. 
		// So, in case of Safari I don't require edge-swiping, any swiping will do. 
		// If you have a better alternative, please let me know!
		if(event.touches[0].pageX < 30 || isSafari || drawerShown)
		{
			touchDown = true;
			touchDownX = event.touches[0].pageX;
			touchDownY = event.touches[0].pageY;
			touchTime = +new Date();
			if(drawerShown)
			{
				touchDiffX = 300-event.touches[0].pageX;
				if(touchDiffX < 0) touchDiffX = 0;
			}
			else
				touchDiffX = 0;
		}
	}, false);
	document.addEventListener('touchmove', function(event) {
		if(drawerAlwaysVisible()) return;
		if(touchDown) //TODO IMPROVE
		{
			var dx = event.changedTouches[0].pageX-touchDownX;
			var dy = event.changedTouches[0].pageY-touchDownY;
			if( Math.abs(dx) > Math.abs(dy))
			{
				startTouchDragging();
				touchDragging = true;
			}
		}
		if(touchDragging)
		{
			var x = (event.changedTouches[0].pageX + touchDiffX)/300.0;
			if(x < 0.0) x = 0.0;
			if(x > 1.0) x = 1.0;
			showDrawer(x);
			event.preventDefault();
		}
	}, false);
	document.addEventListener('touchend', function(event) {
		touchDown = false;
		if(touchDragging)
		{
			stopTouchDragging();
			var now = +new Date();
			var elapsed = now - touchTime;
			var dx = event.changedTouches[0].pageX-touchDownX;

			var speed = dx/elapsed;
			if(elapsed > 500) speed = 0;
			
			if((event.changedTouches[0].pageX + touchDiffX + speed*200) > (drawerShown?225:75))
			{
				showDrawer(1.0);
				drawerShown = true;
			}
			else
			{
				showDrawer(0.0);
				drawerShown = false;
			}
		}
	}, false);

});
