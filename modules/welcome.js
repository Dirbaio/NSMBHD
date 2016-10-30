"use strict"
// Welcomes the user when they join
exports.module = function() {
	this.onUserJoin = function(user) {
		var message = this.settings.message;
		if(!message)
			message = "Welcome back, %!";
		message = message.replace("%", user);
		this.channel.say(message);
	}
}
