"use strict"

exports.module = function() {
	this.onMessage = function(user, message) {
		if (message.toLowerCase().indexOf("y/n") != -1) {
			if (Math.random() > 0.5) {
				this.channel.say(user + ": Yes.");
			} else {
				this.channel.say(user + ": No.");
			}
		}
	}
}
