"use strict";

angular.module('app')

.controller('UserCommentsCtrl', function($scope, ajax) {
	$scope.post = function() {
		ajax('/api/usercomment', {id: $scope.uid, text: $scope.text}, function(_) {
			window.location = window.location;
		});
	};
});