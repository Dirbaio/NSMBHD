"use strict"

var log = require("./Logger.js");

exports.ModuleContainer = function(server, channel)
{
	this.modules = [];
	
	this.channel = channel;
	this.server = server;
	
	this.load = function(moduleSettings)
	{
		for(var moduleName in moduleSettings)
			if(moduleName != "_key")
				this.loadModule(moduleName, moduleSettings[moduleName]);
	}

	this.loadModule = function(moduleName, settings)
	{
		var module = require("./modules/"+moduleName+".js").module;
		var moduleInstance = new module();
		moduleInstance.server = this.server;
		moduleInstance.channel = this.channel;
		moduleInstance.container = this;
		moduleInstance.settings = settings;
		moduleInstance.moduleName = moduleName;
		this.modules.push(moduleInstance);
		log.info("Module "+moduleName+" loaded into server "+this.server+".");
	}
	
	this.start = function()
	{
		this.run("onModuleStart");
	}
	
	this.run = function(func)
	{
		//Create the arguments array to call the functions
		var args = [];
		for(var i = 0; i < arguments.length-1; i++)
			args[i] = arguments[i+1];
		
		for (var i in this.modules) {
			var module = this.modules[i];
			if (module[func]) {
				try {
					module[func].apply(module, args);
				} catch(err) {
					log.error(server+"/"+channel+": Error calling function "+func+" from module "+module.moduleName+": "+err);
				}
			}
		}
	}
}
