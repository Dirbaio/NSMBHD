"use strict"

exports.module = function() {
	this.onMessage = function(user, message) {
		var st = String.fromCharCode(1);
		var os = require("os");
		if (message == st+"VERSION"+st)
			this.server.notice(user, "NinaBot by Nina and Dirbaio. Running on Node.js " + process.versions.node + " (" + os.type() + " " + os.release() + " " + os.arch() + ")");
	}
}
