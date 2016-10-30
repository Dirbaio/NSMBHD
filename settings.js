//TODO: Replace this with JSON

exports.settings = {
	globalNick: "NSMBot",
	globalUserName: "bot",
	globalRealName: "NSMBot",

	defaultCommandPrefix: "!",

	//These modules will be loaded in all channels.
	globalServModules: {
		ctcp: {}
	},

	globalModules: {
		yesno: {},
		chance: {},
		tell: {},
		calc: {},
//		chatbot: {},
		seen: {},
	},

	servers: {
		nolimitnet: {
			address: 'irc.nolimitzone.com',
			port: 6667,
			modules: {},
			channels: {
				"#nsmbhd": {
					modules: {
						report: {port: 1337},
					}
				},
				"#nsmbhd-staff": {
					key: process.env.STAFF_CHANNEL_KEY,
					modules: {
						report: {port: 1338},
					}
				}
			}
		}
	}
};
