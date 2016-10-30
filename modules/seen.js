//Seen -- as requested by Lisa

exports.module = function() {
	this.usersSeenTime = {};

	this.onMessage = function(nick, message) {
		this.updateUserTime(nick);
	}
	
	this.onUserJoin = function(nick) {
		this.updateUserTime(nick);
	}
	
	this.onUserLeave = function(nick) {
		this.updateUserTime(nick);
	}
	
	this.updateUserTime = function(nick) {
		this.usersSeenTime[nick] = Date.now();
	}
	
	this.onCommand_seen = function(nick, args) {
		if (typeof this.usersSeenTime[args] != "undefined") {
			seenTime = Date.now() - this.usersSeenTime[args];
			seenTime = seenTime / 1000;
			if (Math.floor(seenTime / 60 / 60) > 24) {
				returnTime = seenTime / 60 / 60 / 24;
				returnTimeName = "day";
			} else if (Math.floor(seenTime / 60) > 60) {
				returnTime = seenTime / 60 / 60;
				returnTimeName = "hour";
			} else if (Math.floor(seenTime) > 60) {
				returnTime = seenTime / 60;
				returnTimeName = "minute";
			} else {
				returnTime = seenTime;
				returnTimeName = "second";
			}
			
			returnTime = Math.floor(returnTime);

			if (returnTime != 1)
				returnTimeName = returnTimeName + "s";

			response = args + " was last seen " + returnTime + " " + returnTimeName + " ago.";
		} else response = args + " hasn't been seen yet.";
		this.channel.say(response);
	}
}
