"use strict"

var Cleverbot = require('../lib/cleverbot');

exports.module = function()
{
	var cleverbot = new Cleverbot();
	
	this.onMessage = function(user, message) {
		var self = this;
		var nick = this.server.nick.toLowerCase();
		
		message = message.toLowerCase();
		
		if (message.indexOf(nick) != -1) {
			message = message.replace(nick, "");
			cleverbot.write(message, function(resp) {
				self.channel.say(user + ": "+resp["message"]);
			});
		}
	}
}
