"use strict"

exports.module = function() {
	this.onCommand_rolldice = function(nick, command) {
		var dicenumber = Math.ceil(Math.random()*6);
		this.channel.say(nick + ": You rolled a " + dicenumber);
	}
}
