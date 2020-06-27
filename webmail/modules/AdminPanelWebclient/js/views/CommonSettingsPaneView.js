'use strict';

var
	_ = require('underscore'),
	ko = require('knockout'),
	
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	
	App = require('%PathToCoreWebclientModule%/js/App.js'),
	Screens = require('%PathToCoreWebclientModule%/js/Screens.js'),
	
	Ajax = require('modules/%ModuleName%/js/Ajax.js'),
	CAbstractSettingsFormView = require('modules/%ModuleName%/js/views/CAbstractSettingsFormView.js')
;

/**
 * @constructor
 */
function CCommonSettingsPaneView()
{
	CAbstractSettingsFormView.call(this);
	
	this.type = ko.observable('User');
	this.id = ko.observable(0);
	
	this.entityCreateView = ko.computed(function ()
	{
		switch (this.type())
		{
			case 'Tenant':
				return require('modules/%ModuleName%/js/views/EditTenantView.js');
			case 'User':
				return require('modules/%ModuleName%/js/views/EditUserView.js');
		}
	}, this);
	
	this.entityCreateView.subscribe(function () {
		this.updateSavedState();
	}, this);
	
	this.updateSavedState();
}

_.extendOwn(CCommonSettingsPaneView.prototype, CAbstractSettingsFormView.prototype);

CCommonSettingsPaneView.prototype.ViewTemplate = '%ModuleName%_CommonSettingsPaneView';

/**
 * Returns an array with the values of editable fields.
 * 
 * @returns {Array}
 */
CCommonSettingsPaneView.prototype.getCurrentValues = function ()
{
	return this.entityCreateView() ? this.entityCreateView().getCurrentValues() : [];
};

/**
 * Puts values from the global settings object to the editable fields.
 */
CCommonSettingsPaneView.prototype.revertGlobalValues = function ()
{
	if (this.entityCreateView())
	{
		this.entityCreateView().clearFields();
	}
	this.updateSavedState();
};

CCommonSettingsPaneView.prototype.save = function (oParent)
{
	if (this.entityCreateView() && (!_.isFunction(this.entityCreateView().isValidSaveData) || this.entityCreateView().isValidSaveData()))
	{
		Ajax.send('UpdateEntity', {Type: this.type(), Data: this.entityCreateView() ? this.entityCreateView().getParametersForSave() : {}}, function (oResponse) {
			if (oResponse.Result)
			{
				Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_UPDATE_ENTITY_' + this.type().toUpperCase()));
			}
			else
			{
				Screens.showError(TextUtils.i18n('%MODULENAME%/ERROR_UPDATE_ENTITY_' + this.type().toUpperCase()));
			}

			if (oParent && _.isFunction(oParent.currentEntitiesView) && _.isFunction(oParent.currentEntitiesView().requestEntities))
			{
				oParent.currentEntitiesView().requestEntities();
			}

			this.updateSavedState();
		}, this);
	}
};

CCommonSettingsPaneView.prototype.setAccessLevel = function (sEntityType, iEntityId)
{
	this.visible(sEntityType !== '');
	this.type(sEntityType);
	this.id(Types.pInt(iEntityId));
	if (Types.isPositiveNumber(this.id()))
	{
		Ajax.send('GetEntity', {Type: this.type(), Id: this.id()}, function (oResponse, oRequest) {
			if (this.id() === oRequest.Parameters.Id)
			{
				if (this.entityCreateView())
				{
					this.entityCreateView().parse(this.id(), oResponse.Result);
				}
				this.updateSavedState();
			}
		}, this);
	}
	else
	{
		this.updateSavedState();
	}
};

CCommonSettingsPaneView.prototype.onRoute = function ()
{
	App.broadcastEvent('CCommonSettingsPaneView::onRoute::after', {'View': this.entityCreateView(), 'Id': this.id()});
};

module.exports = new CCommonSettingsPaneView();
