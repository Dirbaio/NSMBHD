"use strict"

exports.module = function() {
	this.onCommand_flipcoin = function(nick, command) {
		var coin = Math.ceil(Math.random()*2);
		if (Math.random() < 0.5) {
			this.channel.say("You flipped a heads.");
		} 
		else {
			this.channel.say("You flipped a tails.");
		}
	}
}
