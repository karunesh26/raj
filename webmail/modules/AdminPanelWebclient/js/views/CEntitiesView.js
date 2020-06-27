'use strict';

var
	_ = require('underscore'),
	ko = require('knockout'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	Utils = require('%PathToCoreWebclientModule%/js/utils/Common.js'),
	
	Api = require('%PathToCoreWebclientModule%/js/Api.js'),
	Screens = require('%PathToCoreWebclientModule%/js/Screens.js'),
	
	CPageSwitcherView = require('%PathToCoreWebclientModule%/js/views/CPageSwitcherView.js'),
	
	Popups = require('%PathToCoreWebclientModule%/js/Popups.js'),
	ConfirmPopup = require('%PathToCoreWebclientModule%/js/popups/ConfirmPopup.js'),
	
	Ajax = require('modules/%ModuleName%/js/Ajax.js'),
	Settings = require('modules/%ModuleName%/js/Settings.js')
;

/**
 * Constructor of entities view. Creates, edits and deletes entities.
 * 
 * @param {string} sEntityType Type of entity processed here.
 * 
 * @constructor
 */
function CEntitiesView(sEntityType)
{
	this.sType = sEntityType;
	this.oEntityCreateView = this.getEntityCreateView();
	this.entities = ko.observableArray([]);
	this.totalEntitiesCount = ko.observable(0);
	this.current = ko.observable(0);
	this.showCreateForm = ko.observable(false);
	this.isCreating = ko.observable(false);
	this.hasSelectedEntity = ko.computed(function () {
		var aIds = _.map(this.entities(), function (oEntity) {
			return oEntity.Id;
		});
		return _.indexOf(aIds, this.current()) !== -1;
	}, this);
	
	this.justCreatedId = ko.observable(0);
	this.fChangeEntityHandler = function () {};
	
	ko.computed(function () {
		if (this.justCreatedId() === 0 && !this.showCreateForm() && !this.hasSelectedEntity() && this.entities().length > 0)
		{
			this.fChangeEntityHandler(this.sType, this.entities()[0].Id);
		}
	}, this).extend({ throttle: 1 });
	
	this.checkedEntities = ko.computed(function () {
		return _.filter(this.entities(), function (oEntity) {
			return oEntity.checked();
		}, this);
	}, this);
	this.hasCheckedEntities = ko.computed(function () {
		return this.checkedEntities().length > 0;
	}, this);
	this.deleteCommand = Utils.createCommand(this, this.deleteCheckedEntities, this.hasCheckedEntities);
	this.deactivateCommand = Utils.createCommand(this, function () {}, this.hasCheckedEntities);
	this.selectedCount = ko.computed(function () {
		return this.checkedEntities().length;
	}, this);
	
	this.searchValue = ko.observable('');
	this.newSearchValue = ko.observable('');
	this.isSearchFocused = ko.observable(false);
	this.loading = ko.observable(false);
	this.searchText = ko.computed(function () {
		return TextUtils.i18n('%MODULENAME%/INFO_SEARCH_RESULT', {
			'SEARCH': this.searchValue()
		});
	}, this);
	
	this.oPageSwitcher = new CPageSwitcherView(0, Settings.EntitiesPerPage);
	this.oPageSwitcher.currentPage.subscribe(function () {
		this.loading(true);
		this.requestEntities();
	}, this);
	this.totalEntitiesCount.subscribe(function () {
		this.oPageSwitcher.setCount(this.totalEntitiesCount());
	}, this);
	
	this.aIdListDeleteProcess = [];
}

CEntitiesView.prototype.ViewTemplate = '%ModuleName%_EntitiesView';
CEntitiesView.prototype.CreateFormViewTemplate = '%ModuleName%_EntityCreateFormView';

/**
 * Returns entity edit view for cpecified entity type.
 */
CEntitiesView.prototype.getEntityCreateView = function ()
{
	switch (this.sType)
	{
		case 'Tenant':
			return require('modules/%ModuleName%/js/views/EditTenantView.js');
		case 'User':
			return require('modules/%ModuleName%/js/views/EditUserView.js');
	}
};

/**
 * Requests entity list after showing.
 */
CEntitiesView.prototype.onShow = function ()
{
	this.requestEntities();
};

/**
 * Requests entity list for search string.
 */
CEntitiesView.prototype.search = function ()
{
	this.loading(true);
	this.oPageSwitcher.setPage(1, Settings.EntitiesPerPage);
	this.requestEntities();
};

/**
 * Requests entity list without search string.
 */
CEntitiesView.prototype.clearSearch = function ()
{
	this.newSearchValue('');
	this.loading(true);
	this.requestEntities();
};

/**
 * Requests entity list.
 */
CEntitiesView.prototype.requestEntities = function ()
{
	var oParameters = {
		Type: this.sType,
		Offset: (this.oPageSwitcher.currentPage() - 1) * Settings.EntitiesPerPage,
		Limit: Settings.EntitiesPerPage,
		Search: this.newSearchValue()
	};
	
	this.searchValue(this.newSearchValue());
	Ajax.send('GetEntityList', oParameters, function (oResponse) {
		this.loading(false);
		if (oResponse.Result && _.isArray(oResponse.Result.Items))
		{
			_.each(oResponse.Result.Items, function (oEntity) {
				oEntity.Id = Types.pInt(oEntity.Id);
				oEntity.checked = ko.observable(false);
				oEntity.trottleChecked = function (oItem, oEvent) {
					oEvent.stopPropagation();
					this.checked(!this.checked());
				};
			});
			this.entities(oResponse.Result.Items);
			this.totalEntitiesCount(Types.pInt(oResponse.Result.Count));
			if (this.entities().length === 0)
			{
				this.fChangeEntityHandler(this.sType, undefined, 'create');
			}
			else if (this.justCreatedId() !== 0)
			{
				this.fChangeEntityHandler(this.sType, this.justCreatedId());
			}
			this.aIdListDeleteProcess = [];
		}
	}, this);
};

/**
 * Sets change entity hanler provided by parent view object.
 * 
 * @param {Function} fChangeEntityHandler Change entity handler.
 */
CEntitiesView.prototype.setChangeEntityHandler = function (fChangeEntityHandler)
{
	this.fChangeEntityHandler = fChangeEntityHandler;
};

/**
 * Sets new current entity indentificator.
 * 
 * @param {number} iId New current entity indentificator.
 */
CEntitiesView.prototype.changeEntity = function (iId)
{
	this.current(Types.pInt(iId));
	this.justCreatedId(0);
};

/**
 * Opens create entity form.
 */
CEntitiesView.prototype.openCreateForm = function ()
{
	this.showCreateForm(true);
	this.oEntityCreateView.clearFields();
};

/**
 * Hides create entity form.
 */
CEntitiesView.prototype.cancelCreatingEntity = function ()
{
	this.showCreateForm(false);
};

/**
 * Send request to server to create new entity.
 */
CEntitiesView.prototype.createEntity = function ()
{
	if (this.oEntityCreateView && (!_.isFunction(this.oEntityCreateView.isValidSaveData) || this.oEntityCreateView.isValidSaveData()))
	{
		this.isCreating(true);
		Ajax.send(this.sType === 'Tenant' ? 'CreateTenant' : 'CreateUser', this.oEntityCreateView.getParametersForSave(), function (oResponse) {
			if (oResponse.Result)
			{
				Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_CREATE_ENTITY_' + this.sType.toUpperCase()));
				this.justCreatedId(Types.pInt(oResponse.Result));
				this.cancelCreatingEntity();
			}
			else
			{
				Api.showErrorByCode(oResponse, TextUtils.i18n('%MODULENAME%/ERROR_CREATE_ENTITY_' + this.sType.toUpperCase()));
			}
			this.requestEntities();
			this.isCreating(false);
		}, this);

		this.oEntityCreateView.clearFields();
	}
};

/**
 * Deletes current entity.
 */
CEntitiesView.prototype.deleteCurrentEntity = function ()
{
	this.deleteEntities([this.current()]);
};

CEntitiesView.prototype.deleteCheckedEntities = function ()
{
	var aIdList = _.map(this.checkedEntities(), function (oEntity) {
		return oEntity.Id;
	});
	this.deleteEntities(aIdList);
};

CEntitiesView.prototype.deleteEntities = function (aIdList)
{
	if (Types.isNonEmptyArray(this.aIdListDeleteProcess))
	{
		aIdList = _.difference(aIdList, this.aIdListDeleteProcess);
		this.aIdListDeleteProcess = _.union(aIdList, this.aIdListDeleteProcess);
	}
	else
	{
		this.aIdListDeleteProcess = aIdList;
	}
	if (aIdList.length > 0)
	{
		Popups.showPopup(ConfirmPopup, [
			TextUtils.i18n('%MODULENAME%/CONFIRM_DELETE_' + this.sType.toUpperCase() + '_PLURAL', {}, null, aIdList.length), 
			_.bind(this.confirmedDeleteEntities, this, aIdList), '', TextUtils.i18n('COREWEBCLIENT/ACTION_DELETE')
		]);
	}
};

/**
 * Sends request to the server to delete entity if admin confirmed this action.
 * 
 * @param {array} aIdList
 * @param {boolean} bDelete Indicates if admin confirmed deletion.
 */
CEntitiesView.prototype.confirmedDeleteEntities = function (aIdList, bDelete)
{
	if (bDelete)
	{
		Ajax.send('DeleteEntities', {Type: this.sType, IdList: aIdList}, function (oResponse) {
			if (oResponse.Result)
			{
				Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_DELETE_ENTITIES_' + this.sType.toUpperCase() + '_PLURAL', {}, null, aIdList.length));
			}
			else
			{
				Screens.showError(TextUtils.i18n('%MODULENAME%/ERROR_DELETE_ENTITIES_' + this.sType.toUpperCase() + '_PLURAL', {}, null, aIdList.length));
			}
			this.requestEntities();
		}, this);
	}
	else
	{
		this.aIdListDeleteProcess = [];
	}
};

CEntitiesView.prototype.groupCheck = function ()
{
	var bCheckAll = !this.hasCheckedEntities();
	_.each(this.entities(), function (oEntity) {
		oEntity.checked(bCheckAll);
	});
};

module.exports = CEntitiesView;
