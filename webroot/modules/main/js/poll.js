"use strict";

angular.module('app')

.controller('PollCtrl', function($scope, $sce, $timeout, ajax) {
	$scope.vote = function(choice) {
		ajax('/api/pollvote', {tid: $scope.tid, choice: choice}, function(data) {
			$scope.poll = data;
		});
	}
})