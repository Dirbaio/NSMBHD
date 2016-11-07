"use strict";

angular.module('app')

.factory('urlStyle', function() {
	return function (path) {
		return path;
	};
})