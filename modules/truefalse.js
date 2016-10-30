"use strict"

exports.module = function() {
	this.onMessage = function(user, message) {
		if (message.toLowerCase().indexOf("t/f") != -1) {
			if (Math.random() > 0.5) {
				this.channel.say(user + ": True.");
			} else {
				this.channel.say(user + ": False.");
			}
		}
	}
}
