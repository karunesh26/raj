'use strict';

var
	_ = require('underscore'),
	ko = require('knockout'),
	
	ModulesManager = require('%PathToCoreWebclientModule%/js/ModulesManager.js'),
	
	Popups = require('%PathToCoreWebclientModule%/js/Popups.js'),
	ChangePasswordPopup = ModulesManager.run('ChangePasswordWebclient', 'getChangePasswordPopup'),
	
	Settings = require('modules/%ModuleName%/js/Settings.js')
;

/**
 * @constructor
 */
function CChangeSingleMailAccountPasswordView()
{
	this.oSingleMailAccount = null;
	this.showChangePasswordButton = ko.observable(false);
	this.init();
}

CChangeSingleMailAccountPasswordView.prototype.ViewTemplate = '%ModuleName%_ChangeSingleMailAccountPasswordView';

CChangeSingleMailAccountPasswordView.prototype.init = function ()
{
	var oAccountList = ModulesManager.run('MailWebclient', 'getAccountList', []);
	if (oAccountList && _.isFunction(oAccountList.collection) && oAccountList.collection().length === 1 && !Settings.MailAllowAddAccounts)
	{
		this.oSingleMailAccount = oAccountList.collection()[0];
		this.showChangePasswordButton(true);
	}
};

CChangeSingleMailAccountPasswordView.prototype.changePassword = function ()
{
	if (this.oSingleMailAccount)
	{
		Popups.showPopup(ChangePasswordPopup, [{
			iAccountId: this.oSingleMailAccount.id(),
			sModule: 'Mail',
			bHasOldPassword: true
		}]);
	}
};

module.exports = new CChangeSingleMailAccountPasswordView();
