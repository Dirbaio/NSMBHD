"use strict"
// Gives the user a cookie

var cookies = require("./misc/cookies.js").cookie;
var sizes = require("./misc/sizes.js").size;
var flavors = require("./misc/flavors.js").flavor;
var methods = require("./misc/methods.js").method;
var beverage = require("./misc/beverages.js").bev;

var sayCookie = cookie[Math.floor(Math.random() * cookie.length)];
var saySize = size[Math.floor(Math.random() * size.length)];
var sayFlavor = flavor[Math.floor(Math.random() * flavor.length)];
var sayMethod = method[Math.floor(Math.random() * method.length)];
var sayBev = bev[Math.floor(Math.random() * bev.length)];

exports.module = function() {
	this.onCommand_cookie = function(nick, command) {
		this.channel.say("Here, I'll " + sayMethod + " you a " + sayFlavor + " " + saySize + " " + sayCookie + " cookie with a side of " + sayBev + ".");
	}
}