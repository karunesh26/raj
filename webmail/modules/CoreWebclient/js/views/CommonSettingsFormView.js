'use strict';

var
	_ = require('underscore'),
	ko = require('knockout'),
	
	SettingsUtils = require('%PathToCoreWebclientModule%/js/utils/Settings.js'),
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	
	App = require('%PathToCoreWebclientModule%/js/App.js'),
	ModulesManager = require('%PathToCoreWebclientModule%/js/ModulesManager.js'),
	UserSettings = require('%PathToCoreWebclientModule%/js/Settings.js'),
	
	Enums = window.Enums,
	CAbstractSettingsFormView
;

if (App.getUserRole() === Enums.UserRole.SuperAdmin)
{
	CAbstractSettingsFormView = ModulesManager.run('AdminPanelWebclient', 'getAbstractSettingsFormViewClass');
}
else
{
	CAbstractSettingsFormView = ModulesManager.run('SettingsWebclient', 'getAbstractSettingsFormViewClass');
}

/**
 * @constructor
 */
function CCommonSettingsFormView()
{
	CAbstractSettingsFormView.call(this);
	
	this.bAdmin = App.getUserRole() === Enums.UserRole.SuperAdmin;
	
	this.aThemes = UserSettings.ThemeList;
	this.aLanguages = _.clone(UserSettings.LanguageList);
	this.aDateFormats = SettingsUtils.getDateFormatsForSelector();
	
	if (this.bAdmin)
	{
		this.aLanguages.unshift({value: 'autodetect', name: TextUtils.i18n('%MODULENAME%/LABEL_AUTODETECT')});
	}
	
	/* Editable fields */
	this.selectedTheme = ko.observable(UserSettings.Theme);
	this.selectedLanguage = ko.observable(this.bAdmin && UserSettings.AutodetectLanguage ? 'autodetect' : UserSettings.Language);
	this.autoRefreshInterval = ko.observable(UserSettings.AutoRefreshIntervalMinutes);
	this.aRefreshIntervals = [
		{name: TextUtils.i18n('%MODULENAME%/LABEL_REFRESH_OFF'), value: 0},
		{name: TextUtils.i18n('%MODULENAME%/LABEL_MINUTES_PLURAL', {'COUNT': 1}, null, 1), value: 1},
		{name: TextUtils.i18n('%MODULENAME%/LABEL_MINUTES_PLURAL', {'COUNT': 3}, null, 3), value: 3},
		{name: TextUtils.i18n('%MODULENAME%/LABEL_MINUTES_PLURAL', {'COUNT': 5}, null, 5), value: 5},
		{name: TextUtils.i18n('%MODULENAME%/LABEL_MINUTES_PLURAL', {'COUNT': 10}, null, 10), value: 10},
		{name: TextUtils.i18n('%MODULENAME%/LABEL_MINUTES_PLURAL', {'COUNT': 15}, null, 15), value: 15},
		{name: TextUtils.i18n('%MODULENAME%/LABEL_MINUTES_PLURAL', {'COUNT': 20}, null, 20), value: 20},
		{name: TextUtils.i18n('%MODULENAME%/LABEL_MINUTES_PLURAL', {'COUNT': 30}, null, 30), value: 30}
	];
	this.timeFormat = ko.observable(UserSettings.timeFormat());
	this.selectedDateFormat = ko.observable(UserSettings.dateFormat());
	this.desktopNotifications = ko.observable(UserSettings.AllowDesktopNotifications);
	/*-- Editable fields */
	
	this.allowChangeDateFormat = ko.computed(function () {
		return !this.bAdmin && UserSettings.UserSelectsDateFormat;
	}, this);
	this.isDesktopNotificationsEnable = ko.observable((window.Notification && window.Notification.permission !== 'denied'));
	this.desktopNotifications.subscribe(function (bChecked) {
		var self = this;
		if (bChecked && window.Notification.permission === 'default')
		{
			window.Notification.requestPermission(function (sPermission) {
				if (sPermission === 'denied')
				{
					self.desktopNotifications(false);
					self.isDesktopNotificationsEnable(false);
				}
			});
		}
	}, this);
}

_.extendOwn(CCommonSettingsFormView.prototype, CAbstractSettingsFormView.prototype);

CCommonSettingsFormView.prototype.ViewTemplate = 'CoreWebclient_CommonSettingsFormView';

/**
 * Returns an array with the values of editable fields.
 * 
 * @returns {Array}
 */
CCommonSettingsFormView.prototype.getCurrentValues = function ()
{
	return [
		this.selectedTheme(),
		this.selectedLanguage(),
		this.autoRefreshInterval(),
		this.timeFormat(),
		this.selectedDateFormat(),
		this.desktopNotifications()
	];
};

/**
 * Puts values from the global settings object to the editable fields.
 */
CCommonSettingsFormView.prototype.revertGlobalValues = function ()
{
	this.selectedTheme(UserSettings.Theme);
	this.selectedLanguage(this.bAdmin && UserSettings.AutodetectLanguage ? 'autodetect' : UserSettings.Language);
	this.autoRefreshInterval(UserSettings.AutoRefreshIntervalMinutes);
	this.timeFormat(UserSettings.timeFormat());
	this.selectedDateFormat(UserSettings.dateFormat());
	this.desktopNotifications(UserSettings.AllowDesktopNotifications);
};

/**
 * Gets values from the editable fields and prepares object for passing to the server and saving settings therein.
 * 
 * @returns {Object}
 */
CCommonSettingsFormView.prototype.getParametersForSave = function ()
{
	var oParameters = {
		'Theme': this.selectedTheme(),
		'TimeFormat': this.timeFormat()
	};
	
	if (this.bAdmin)
	{
		if (this.selectedLanguage() === 'autodetect')
		{
			oParameters['AutodetectLanguage'] = true;
		}
		else
		{
			oParameters['AutodetectLanguage'] = false;
			oParameters['Language'] = this.selectedLanguage();
		}
	}
	else
	{
		oParameters['AutoRefreshIntervalMinutes'] = Types.pInt(this.autoRefreshInterval());
		oParameters['AllowDesktopNotifications'] = this.desktopNotifications();
		oParameters['Language'] = this.selectedLanguage();
		if (this.allowChangeDateFormat())
		{
			oParameters['DateFormat'] = this.selectedDateFormat();
		}
	}
	
	return oParameters;
};

/**
 * Applies saved values of settings to the global settings object.
 * 
 * @param {Object} oParameters Object that have been obtained by getParameters function.
 */
CCommonSettingsFormView.prototype.applySavedValues = function (oParameters)
{
	if (oParameters.Theme !== UserSettings.Theme || oParameters.Language !== UserSettings.Language && !this.bAdmin)
	{
		window.location.reload();
	}
	else
	{
		UserSettings.update(oParameters.AutoRefreshIntervalMinutes,
			oParameters.Theme, oParameters.Language,
			oParameters.TimeFormat, oParameters.DateFormat, oParameters.AllowDesktopNotifications);
	}
};

CCommonSettingsFormView.prototype.setAccessLevel = function (sEntityType, iEntityId)
{
	this.visible(sEntityType === '');
};

module.exports = new CCommonSettingsFormView();
