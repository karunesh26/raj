'use strict';

var
	Ajax = require('%PathToCoreWebclientModule%/js/Ajax.js'),
	
	Settings = require('modules/%ModuleName%/js/Settings.js')
;

module.exports = {
	send: function (sMethod, oParameters, fResponseHandler, oContext) {
		Ajax.send(Settings.ServerModuleName, sMethod, oParameters, fResponseHandler, oContext);
	}
};