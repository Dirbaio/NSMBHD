"use strict"

exports.module = function() {
	this.onCommand_time = function(nick, command) {
		this.channel.say("Current date and time is " + (new Date()).toString());
	}
}
