"use strict";

angular.module('app')

.factory('urlStyle', function() {
	return function (path) {
    	if(path == '/')
    		return './';
    	return './?' + path;
	};
})