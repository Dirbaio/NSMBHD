
var logLevel = 4;

exports.debug = function(what) {if(logLevel >= 4) console.log("[DEBUG]   "+what); }
exports.info  = function(what) {if(logLevel >= 3) console.log("[INFO]    "+what); }
exports.warning  = function(what) {if(logLevel >= 2) console.log("[WARNING] "+what); }
exports.error = function(what) {if(logLevel >= 1) console.log("[ERROR]   "+what); }
exports.critical  = function(what) {if(logLevel >= 1) console.log("[CRITICAL]"+what); }

