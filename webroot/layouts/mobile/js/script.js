'use strict';

$(document).ready(function() {
	var userAgent = window.navigator.userAgent;
	var isSafari = userAgent.match(/iPad/i) || userAgent.match(/iPhone/i);

	var hideTimeout = false;
	var stuffShown = false;

	var drawer = document.getElementById('drawer');
	var overlay = document.getElementById('drawer-overlay');
	var toggle = document.getElementById('drawer-toggle');

	function showDrawer(percent)
	{
		if(hideTimeout)
		{
			clearTimeout(hideTimeout);
			hideTimeout = false;
		}
			
		if(percent == 0) {
			hideTimeout = setTimeout(function() {
				overlay.style.cssText = 'visibility: hidden';
			}, 500);
		}

		overlay.style.cssText = 'visibility: visible; opacity: '+percent*0.75;
		
		var t = 300 * (percent-1);
		drawer.style.cssText = '-webkit-transform: translateX('+t+'px); transform: translateX('+t+'px)';
	}

	var drawerShown = false;

	function resetDrawer()
	{
		overlay.style.cssText = '';
		drawer.style.cssText = '';

		drawerShown = false;
		stuffShown = false;
	}

	var touchDown = false;
	var touchDragging = false;
	var touchDownX = 0;
	var touchDownY = 0;
	var touchDownScroll = 0;
	var touchDiffX = 0;
	var touchTime = 0;

	//Returns true if drawer should be always visible (not in mobile mode)
	function drawerAlwaysVisible() {
		return window.innerWidth >= 768;
	}

	function startTouchDragging()
	{
		touchDragging = true;
		drawer.className = 'dragging';
		overlay.className = 'dragging';
	}

	function stopTouchDragging()
	{
		touchDragging = false;
		drawer.className = '';
		overlay.className = '';
	}

	overlay.addEventListener('click', function() {
		drawerShown = false;
		showDrawer(0.0);
	});
	toggle.addEventListener('click', function(e) {
		drawerShown = !drawerShown;
		if (drawerShown)
			showDrawer(1.0);
		else
			showDrawer(0.0);
		e.preventDefault();
		return false;
	});

	window.addEventListener('resize', function(){
		if(drawerAlwaysVisible())
			resetDrawer();
	});

	document.addEventListener('touchstart', function(event) {
		if(drawerAlwaysVisible()) return;
		// On Safari edge-swipe is used for back navigation. 
		// So, in case of Safari I don't require edge-swiping, any swiping will do. 
		// If you have a better alternative, please let me know!
		if(event.touches[0].pageX < (isSafari?80:30) || drawerShown)
		{
			touchDown = true;
			touchDownScroll = window.pageYOffset;
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
		if(touchDown)
		{
			//Gesture recognition.
			//If it made the page scroll, abort
			if(touchDownScroll != window.pageYOffset)
				touchDown = false;

			var dx = event.changedTouches[0].pageX-touchDownX;
			var dy = event.changedTouches[0].pageY-touchDownY;

			if(Math.abs(dy) > 20)
				touchDown = false;

			if(Math.abs(dx) > Math.abs(dy) && touchDown)
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
