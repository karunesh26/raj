'use strict';

module.exports = function (oAppData) {
	var
		_ = require('underscore'),
		$ = require('jquery'),
		ko = require('knockout'),
		
		Routing = require('%PathToCoreWebclientModule%/js/Routing.js'),
		TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
		
		Ajax = require('%PathToCoreWebclientModule%/js/Ajax.js'),
		App = require('%PathToCoreWebclientModule%/js/App.js'),
		
		Settings = require('modules/%ModuleName%/js/Settings.js'),
		
		bNormalUser = App.getUserRole() === Enums.UserRole.NormalUser,
		bAnonymUser = App.getUserRole() === Enums.UserRole.Anonymous,
		
		fGetErrorMessageByCode = function (oError) {
			switch (oError.ErrorCode)
			{
				case Settings.EOAuthIntegratorError.ServiceNotAllowed: return TextUtils.i18n('%MODULENAME%/ERROR_SERVICE_NOT_ALLOWED');
				case Settings.EOAuthIntegratorError.AccountNotAllowedToLogIn: return TextUtils.i18n('%MODULENAME%/ERROR_ACCOUNT_NOT_ALLOWED');
				case Settings.EOAuthIntegratorError.AccountAlreadyConnected: return TextUtils.i18n('%MODULENAME%/ERROR_ACCOUNT_ALREADY_CONNECTED');
			}
			return '';
		}

	;

	Settings.init(oAppData);
	
	if (bAnonymUser)
	{
		return {
			start: function (ModulesManager) {
				Settings.oauthServices = ko.observableArray([]);
				var fGetInvitationLinkHash = function () {
					var aHashArray = Routing.getCurrentHashArray();
					if (aHashArray.length >= 2 && aHashArray[0] === Settings.RegisterModuleHash)
					{
						return aHashArray[1];
					}
					return '';
				};
				
				var fInitialize = function (oParams) {
					if ('CLoginView' === oParams.Name || 'CRegisterView' === oParams.Name)
					{
						var sInvitationLinkHash = fGetInvitationLinkHash();
						oParams.View.externalAuthClick = function (sSocialName) {
							$.cookie('oauth-redirect', 'CLoginView' === oParams.Name ? 'login' : 'register');
							$.cookie('oauth-scopes', 'auth');

							window.location.href = '?oauth=' + sSocialName;
						};

						oParams.View.oauthServices = Settings.oauthServices;
					}
				};
				
				Ajax.send(Settings.ServerModuleName, 'GetServices', null, function (oResponse) {
					Settings.oauthServices(oResponse.Result);
				}, this);

				App.subscribeEvent('StandardLoginFormWebclient::ConstructView::after', fInitialize);
				App.subscribeEvent('StandardRegisterFormWebclient::ConstructView::after', fInitialize);
			},
			getErrorMessageByCode: fGetErrorMessageByCode
		};
	}
	
	if (bNormalUser)
	{
		return {
			start: function (ModulesManager) {
				var fGetAccounts = function () {
					Ajax.send(Settings.ServerModuleName, 'GetAccounts', null, function (oResponse) {
						var iAuthAccountCount = 0;
						if (_.isArray(oResponse.Result))
						{
							_.each(oResponse.Result, function (oAccount) {
								if (oAccount.Scopes.indexOf('auth') !== -1)
								{
									iAuthAccountCount++;
								}
							});
						}
						Settings.userAccountsCount(iAuthAccountCount);
					});
				};
				App.subscribeEvent('OAuthAccountChange::after', function () {
					fGetAccounts();
				});
				fGetAccounts();
			},
			getCreateLoginPasswordView: function () {
				return require('modules/%ModuleName%/js/views/CreateLoginPasswordView.js');
			},
			getErrorMessageByCode: fGetErrorMessageByCode
		};
	}
	
	return null;
};
