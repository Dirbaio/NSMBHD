//Do Not Slap -- because fishbots aren't fun!

exports.module = function() {
	this.onMessage = function(user, message) {
		if (message.toLowerCase().match(/.*slaps .* around a bit with a large fishbot.*/) !== null) {
			this.server.sendCommand("KICK", this.channel.channelName + " " + user + " :Why are you so rude?");
		}
	}
}
