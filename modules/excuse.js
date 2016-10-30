"use strict"
// Prints a random excuse

var excuses = require("./misc/excuse.js").excuses;
exports.module = function() {
	this.onCommand_excuse = function(nick, command) {
		var sayExcuse = excuse[Math.floor(Math.random() * excuse.length)];
		this.channel.say(sayExcuse);
	}
}