"use strict"

exports.module = function() {
	this.onCommand_chance = function(nick, command, args) {
		var rand = Math.ceil(Math.random()*100);
		this.channel.say(nick + ": " + rand);
	}
}
