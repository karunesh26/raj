'use strict';

module.exports = function (oAppData) {
	var 
		App = require('%PathToCoreWebclientModule%/js/App.js'),
		Promise = require("bluebird")
	;
	
	if (App.getUserRole() === Enums.UserRole.SuperAdmin)
	{
		var
			_ = require('underscore'),
			
			TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
			
			Settings = require('modules/%ModuleName%/js/Settings.js'),
			
			aAdminPanelTabsParams = []
		;

		Settings.init(oAppData);
		
		return {
			start: function () {
				aAdminPanelTabsParams.push({
					GetTabView: function(resolve) {
						require.ensure(
							['modules/%ModuleName%/js/views/DbAdminSettingsView.js'],
							function() {
								resolve(require('modules/%ModuleName%/js/views/DbAdminSettingsView.js'));
							},
							"admin-bundle"
						);
					},
					TabName: Settings.HashModuleName + '-db',
					TabTitle: TextUtils.i18n('%MODULENAME%/LABEL_DB_SETTINGS_TAB')
				});
				aAdminPanelTabsParams.push({
					GetTabView: function(resolve) {
						require.ensure(
							['modules/%ModuleName%/js/views/SecurityAdminSettingsView.js'],
							function() {
								resolve(require('modules/%ModuleName%/js/views/SecurityAdminSettingsView.js'));
							},
							"admin-bundle"
						);
					},
					TabName: Settings.HashModuleName + '-security',
					TabTitle: TextUtils.i18n('%MODULENAME%/LABEL_SECURITY_SETTINGS_TAB')
				});
				aAdminPanelTabsParams.push({
					GetTabView: function(resolve) {
						require.ensure(
							['modules/%ModuleName%/js/views/CommonSettingsPaneView.js'],
							function() {
								resolve(require('modules/%ModuleName%/js/views/CommonSettingsPaneView.js'));
							},
							"admin-bundle"
						);
					},
					TabName: 'common',
					TabTitle: TextUtils.i18n('%MODULENAME%/LABEL_COMMON_SETTINGS_TAB')
				});
				aAdminPanelTabsParams.push({
					GetTabView: function(resolve) {
						require.ensure(
							['modules/%ModuleName%/js/views/AboutAdminSettingsView.js'],
							function() {
								resolve(require('modules/%ModuleName%/js/views/AboutAdminSettingsView.js'));
							},
							"admin-bundle"
						);
					},
					TabName: 'about',
					TabTitle: TextUtils.i18n('%MODULENAME%/LABEL_ABOUT_SETTINGS_TAB')
				});
			},
			getScreens: function () {
				var oScreens = {};
				oScreens[Settings.HashModuleName] = function () {
					
					return new Promise(function(resolve, reject) {
						require.ensure(
							['modules/%ModuleName%/js/views/SettingsView.js'],
							function(require) {
								var
									oSettingsView = require('modules/%ModuleName%/js/views/SettingsView.js'),
									aPromises = []
								;
								
								_.each(aAdminPanelTabsParams, function (oParams) {
									var oPromise = oSettingsView.registerTab(oParams.GetTabView, oParams.TabName, oParams.TabTitle);
									
									if (oPromise)
									{
										aPromises.push(oPromise);
									}
								});
								
								Promise.all(aPromises).then(function () { 
									oSettingsView.sortRegisterTabs();
									resolve(oSettingsView);
								}, function () {
									oSettingsView.sortRegisterTabs();
									resolve(oSettingsView);
								});
							},
							"admin-bundle"
						);
					});
				};
				return oScreens;
			},
			getAbstractSettingsFormViewClass: function () {
				return require('modules/%ModuleName%/js/views/CAbstractSettingsFormView.js');
			},
			registerAdminPanelTab: function (fGetTabView, sTabName, sTabTitle) {
				aAdminPanelTabsParams.push({
					GetTabView: fGetTabView,
					TabName: sTabName,
					TabTitle: sTabTitle
				});
			},
			setAddHash: function (aAddHash) {
				var SettingsView = require('modules/%ModuleName%/js/views/SettingsView.js');
				SettingsView.setAddHash(aAddHash);
			}
		};
	}
	
	return null;
};
