
exports.module = function()
{
	this.calc = function(expr, callback)
	{
		var process = require('child_process');

		child = process.exec('timeout 1s calc -pdqm0',
			function (error, stdout, stderr) {
				stdout = stdout.trim();
				if(stdout != "" && stdout != null)
				{
					var lines = stdout.split("\n").length;
					var len = stdout.length;
					if(lines > 4 || len > 400)
						callback("Oops. Output is too long. Sorry!");
					else
	 					callback(stdout);
				}
				else
					callback("Oops! "+stderr.trim());
			});
		child.stdin.write(expr);
		child.stdin.end();
	};

	this.onCommand_calc = function(user, args)
	{
		var self = this;
		self.calc(args, function(result) { self.channel.say(result); });
	}
}

