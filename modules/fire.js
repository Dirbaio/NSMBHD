/* Bot: My object is on fire!
You: /me uses the firehose
*/
"use strict"
exports.module = function() {
	this.onCommand_fire = function(nick, command) {
		
		var onFire = false;
		var OBJECTS = ["pants", "house", "cat", "mouse"],
		objectOnFire = null;

		if (!objectOnFire) {
			objectOnFire = OBJECTS[Math.floor(Math.random() * OBJECTS.length)];
		}
			
		this.channel.say("HELP! My " + objectOnFire + " is on fire! Please use the firehose!");
			
		this.onMessage = function(user, message) {
			if (message.toLowerCase().match(/.*uses the firehose.*/) !== null) {
					this.channel.say("Thanks for saving my " + objectOnFire + "!");
			}
		}
	}
}
