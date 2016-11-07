"use strict";

angular.module('app')

.controller('UploadCtrl', function($scope, $upload) {
	$scope.onFileSelect = function($files) {
		$scope.upload = $upload.upload({
			url: '/api/upload',
			method: 'POST',
			file: $files[0],
		}).progress(function(evt) {
			console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
		}).success(function(data, status, headers, config) {
			$scope.uploadedFile = angular.fromJson(data);
		});
	}
})