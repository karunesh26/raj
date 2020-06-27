'use strict';

module.exports = function (oAppData) {
	var App = require('%PathToCoreWebclientModule%/js/App.js');
	
	if (App.getUserRole() === Enums.UserRole.NormalUser)
	{
		var Settings = require('modules/%ModuleName%/js/Settings.js');

		Settings.init(oAppData);

		return {
			start: function (ModulesManager) {
				ModulesManager.run(
					'SettingsWebclient',
					'registerSettingsTabSection', 
					[
						function () { return require('modules/%ModuleName%/js/views/ChangeSingleMailAccountPasswordView.js'); },
						'common',
						'common'
					]
				);
			},
			getChangePasswordPopup: function () {
				return require('modules/%ModuleName%/js/popups/ChangePasswordPopup.js');
			}
		};
	}
	
	return null;
};
