"use strict"

var os = require("os");
var net = require("net");

var mc = require("./ModuleContainer.js");
var log = require("./Logger.js");

exports.Server = function(serverSettings)
{
	this.address = serverSettings.address;
	this.port = serverSettings.port;

	this.nick = serverSettings.nick;
	this.userName = serverSettings.userName;
	this.realName = serverSettings.realName;
	this.commandPrefix = serverSettings.commandPrefix;

	this.channels = {};

	this.connected = false;

	this.modules = new mc.ModuleContainer(this);
	this.modulesStarted = false;

	this.toString = function()
	{
		return this.address+":"+this.port;
	}

	// Connects to the server and starts listening for incoming data.
	this.connect = function()
	{
		//Check that we're not connected
		if(this.connected)
			throw new Error("Connecting to an already connected server");

		log.info("Connecting to "+this+"...");

		var self = this;

		this.socket = net.createConnection(this.port, this.address);
		this.socket.on("connect", function() {
			// Line reading below from
			// https://github.com/martynsmith/node-irc/blob/master/lib/irc.js
			log.info("Successfully connected to "+self+"...");

			var buffer = '';
			self.socket.addListener("data", function (chunk)
			{
				buffer += chunk;
				var lines = buffer.split("\r\n");
				buffer = lines.pop();
				lines.forEach(function (line)
				{
					log.debug(">> "+line);
					var message = parseMessage(line, false);
					self.gotRawMessage(message);
				});
			});

			self.sendCommand("NICK", self.nick);
			self.sendCommand("USER", self.userName+" "+self.userName+" "+self.address+" :"+self.realName);
		});

		var reconnect = function() {
			if(!self.connected) return;
			self.connected = false;
			log.warning("Disconnected from "+self+". Reconnecting in 5s...");
			setTimeout(function() {
				self.connect();
			}, 5000);
		};

		this.socket.on("timeout", reconnect);
		this.socket.on("error", reconnect);
		this.socket.on("close", reconnect);

		this.connected = true;
	}

	this.addChannel = function(channel)
	{
		this.channels[channel.channelName] = channel;
	}

	this.gotRawMessage = function(message)
	{
		switch(message.command)
		{
			case "PRIVMSG":
				var channel = this.channels[message.args[0].toLowerCase()];
				var text = message.args[1].trim();
				if (channel)
				{
					if (text.indexOf(this.commandPrefix) == 0) {
						var cmdString = text.substring(this.commandPrefix.length);
						var command = cmdString.split(" ")[0];
						var cmdarguments = cmdString.substring(command.length + 1);
						channel.onCommand(message.nick, command, cmdarguments);
					} else
						channel.onMessage(message.nick, text);
				} else {
					this.modules.run('onMessage', message.nick, text);
				}
				break;
			case "JOIN":
				var channel = this.channels[message.args[0]];
				if(channel)
					channel.onUserJoin(message.nick);
				break;
			case "PART":
				var channel = this.channels[message.args[0]];
				if(channel)
					channel.onUserLeave(message.nick);
				break;
			//Changed this to 251; it's a safer assumption.
			case "251":
				if(!this.modulesStarted)
					this.modules.start();
				this.modulesStarted = true;
				for(var channelName in this.channels)
				{
					var channel = this.channels[channelName];
					var key = '';
					if(channel.key) key = channel.key;
					this.sendCommand("JOIN", channel+" "+key);
					channel.modules.start();
				}
				break;
			case "PING":
				this.sendCommand("PONG", message.args[0]);
				break;
			case true:
				modules.run(message.command, message);
				break;
			case "433":
				this.nick += "_";
				this.sendCommand("NICK", this.nick);
				this.sendCommand("USER", this.userName+" "+this.userName+" "+this.address+" :"+this.realName);
				break;
			case "376":
				this.sendCommand("MODE", this.nick+ " +B");
		}
	}

    this.sendCommand = function(command, args)
    {
		//Check all this to avoid hax
		command = command.replace("\n", "");
		command = command.replace("\r", "");
		args = args.replace("\n", "");
		args = args.replace("\r", "");

		log.debug("<< " + command + " " + args);
    	this.socket.write(command+" "+args+"\r\n");
    }

	//TODO: Functions to say stuff, send PMs, maybe more.
	//But we should only add what we need. No bloat :)

	this.notice = function(nick, message)
	{
		this.sendCommand("NOTICE", nick+" :"+message);
	}

	/*
	* parseMessage(line, stripColors)
	*
	* takes a raw "line" from the IRC server and turns it into an object with
	* useful keys
	*
	* From: https://github.com/martynsmith/node-irc/blob/master/lib/irc.js
	*/
	function parseMessage(line, stripColors) { // {{{
		var message = {};
		var match;

		if (stripColors) {
		    line = line.replace(/[\x02\x1f\x16\x0f]|\x03\d{0,2}(?:,\d{0,2})?/g, "");
		}

		// Parse prefix
		if ( match = line.match(/^:([^ ]+) +/) ) {
		    message.prefix = match[1];
		    line = line.replace(/^:[^ ]+ +/, '');
		    if ( match = message.prefix.match(/^([_a-zA-Z0-9\[\]\\`^{}|-]*)(!([^@]+)@(.*))?$/) ) {
		        message.nick = match[1];
		        message.user = match[3];
		        message.host = match[4];
		    }
		    else {
		        message.server = message.prefix;
		    }
		}

		// Parse command
		match = line.match(/^([^ ]+) */);
		message.command = match[1];
		message.rawCommand = match[1];
		message.commandType = 'normal';
		line = line.replace(/^[^ ]+ +/, '');

		message.args = [];
		var middle, trailing;

		// Parse parameters
		if ( line.indexOf(':') != -1 ) {
		    var index = line.indexOf(':');
		    middle = line.substr(0, index).replace(/ +$/, "");
		    trailing = line.substr(index+1);
		}
		else {
		    middle = line;
		}

		if ( middle.length )
		    message.args = middle.split(/ +/);

		if ( typeof(trailing) != 'undefined' && trailing.length )
		    message.args.push(trailing);

		return message;
	}

}
