"use strict";

angular.module('app')

.controller('PageCtrl', function($scope, $window, ajax) {
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


	$scope.toggleMobileLayout = function(enabled) {
		setCookie("mobileversion", enabled, 20*365*24*60*60, "/");
		$window.location.reload();
	}

	$scope.deletePost = function(pid) {
		var reason = prompt("Enter a reason for deletion:");
		
		if(reason === null)
			return;

		$scope.doAction('/api/deletepost', {pid: pid, del:1, reason:reason});
	}

	$scope.renameThread = function(tid) {
		var name = prompt("Enter new thread name");
		
		if(name === null)
			return;

		$scope.doAction('/api/renamethread', {tid: tid, name: name});
	}

	$scope.doAction = function(api, args) {
		ajax(api, args, function(redirect) {
			window.location = redirect;
		});
	}
})