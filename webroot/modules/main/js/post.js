"use strict";


function postBoxCtrlFactory($scope, $sce, $timeout, ajax, $upload) {
	$scope.data = {};

	if(typeof($scope.data.text) != 'string')
		$scope.data.text = '';

	$scope.postButtonText = 'Post';

	$scope.dirty = false;
	$scope.saving = false;
	$scope.saved = false;

	$scope.started = false;

	$scope.submit = function() {
		ajax($scope.postbox.submitApi, $scope.data, function(redirect) {
			window.location = redirect;
		});
	};

	$scope.$watch("data", function() {
		// We have to ignore the first change, which is when
		// ng-init executes and changes stuff: it shouldn't mark
		// stuff as dirty.
		if(!$scope.started) {
			$scope.started = true;
			return;
		}

		if($scope.dirty) return;

		$scope.dirty=true;
		$timeout(function() {
			$scope.save();
		}, 5000);
	}, true);

	$scope.save = function(callback) {
		if(!$scope.dirty) return;

		$scope.saving = true;
		var target = 0;
		if($scope.postbox.draftTarget)
			target = $scope.postbox.draftTarget();
		ajax('/api/savedraft', {type: $scope.postbox.draftType, target: target, data: $scope.data}, function() {
			$scope.saving = false;
			$scope.saved = true;
			$scope.dirty = false;

			if(callback)
				callback();
		});
	};

	$scope.preview = function() {
		ajax('/api/preview', $scope.data, function(data) {
			$scope.previewhtml = $sce.trustAsHtml(data);
		});
	};

	$scope.add = function(before, after) {
		if(after === undefined)
			after = '';

		// Totally not Angular-way, but it's the simplest way possible.
		var textEditor = document.getElementById('text');

		var oldSelS = textEditor.selectionStart;
		var oldSelE = textEditor.selectionEnd;
		if(after == '')
			oldSelS = oldSelE;
		if(!$scope.editorFocused)
			oldSelS = oldSelE = $scope.data.text.length;

		var scroll = textEditor.scrollTop;
		var selectedText = $scope.data.text.substr(oldSelS, oldSelE - oldSelS);

		$scope.data.text = $scope.data.text.substr(0, oldSelS) + before + selectedText + after + textEditor.value.substr(oldSelE);

		$timeout(function() {
			textEditor.selectionStart = oldSelS + before.length;
			textEditor.selectionEnd = oldSelS + before.length + selectedText.length;
			textEditor.scrollTop = scroll;
			textEditor.focus();

			$scope.$apply($scope.changed);
		}, 0, false);
	};

	// Just by instantiating one controller of these
	// we will get draft autosaving in the entire page. Nice, hm?
	$(document).on("click", "a", function(e) {
		if(e.currentTarget.onclick)
			return true;

		if($scope.dirty) {
			$scope.$apply(function() {
				$scope.save(function() {
					window.location = e.currentTarget.href;
				});
			});

			return false;
		}
		else
			return true;
	});

	$scope.validate = function() {
		if(typeof($scope.data) !== 'object')
			$scope.data = {};
		if(typeof($scope.data.text) !== 'string')
			$scope.data.text = '';

		$scope.started = false;
	};

	$scope.onFileSelect = function($files) {
		var file = $files[0];
		$scope.uploading = true;
		$scope.upload = $upload.upload({
			url: '/api/upload',
			method: 'POST',
			file: file,
		}).progress(function(evt) {
			$scope.uploadProgress = parseInt(100.0 * evt.loaded / evt.total);
		}).success(function(data, status, headers, config) {
			$scope.uploading = false;
			var fileurl = angular.fromJson(data);
			if(file.type.indexOf('image') > -1)
				$scope.add('[img]'+fileurl+'[/img]');
			else
				$scope.add('[url='+fileurl+']'+file.name+'[/url]');
		}).error(function() {
			$scope.uploading = false;
			alert('Error uploading :(');
		});
	};
}


angular.module('app')

.controller('NewReplyCtrl', function($scope, $sce, $timeout, ajax, $upload) {
	postBoxCtrlFactory($scope, $sce, $timeout, ajax, $upload);

	$scope.postbox = {
		submitApi: '/api/newreply',
		draftType: 0,
		draftTarget: function() { return $scope.data.tid; }
	};

	$scope.quote = function(pid) {
		ajax('/api/getquote', {pid: pid}, $scope.add);

		$('html, body').animate({
			scrollTop: $("#text").offset().top-60
		}, 600);
	};
})

.controller('NewThreadCtrl', function($scope, $sce, $timeout, ajax, $upload) {
	postBoxCtrlFactory($scope, $sce, $timeout, ajax, $upload);

	$scope.postbox = {
		submitApi: '/api/newthread',
		draftType: 1,
		draftTarget: function() { return $scope.data.fid; }
	};
	$scope.postButtonText = 'Post thread';
	
	var oldValidate = $scope.validate;
	$scope.validate = function() {
		oldValidate();
		if(typeof($scope.data.pollchoices) !== 'object' || $scope.data.pollchoices.length < 2)
			$scope.data.pollchoices = [{text: ''}, {text: ''}];
	};
})

.controller('EditPostCtrl', function($scope, $sce, $timeout, ajax, $upload) {
	postBoxCtrlFactory($scope, $sce, $timeout, ajax, $upload);

	$scope.postbox = {
		submitApi: '/api/editpost',
		draftType: 2,
		draftTarget: function() { return $scope.data.pid; }
	};

	$scope.postButtonText = 'Edit post';
})

.controller('NewPrivateCtrl', function($scope, $sce, $timeout, ajax, $upload) {
	postBoxCtrlFactory($scope, $sce, $timeout, ajax, $upload);

	$scope.postbox = {
		submitApi: '/api/newprivate',
		draftType: 3,
	};
	$scope.postButtonText = 'Create conversation';

	var oldValidate = $scope.validate;
	$scope.validate = function() {
		oldValidate();
		if(typeof($scope.data.recipients) !== 'object' || $scope.data.recipients.length < 1)
			$scope.data.recipients = [0];
	};
})


.controller('PrivateReplyCtrl', function($scope, $sce, $timeout, ajax, $upload) {
	postBoxCtrlFactory($scope, $sce, $timeout, ajax, $upload);

	$scope.postbox = {
		submitApi: '/api/privatereply',
		draftType: 4,
		draftTarget: function() { return $scope.data.tid; }
	};

	$scope.quote = function(pid) {
		ajax('/api/getprivatequote', {pid: pid}, $scope.add);

		$('html, body').animate({
			scrollTop: $("#text").offset().top-60
		}, 600);
	};
})
;