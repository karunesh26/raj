webpackJsonp([2],{

/***/ 232:
/*!***************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/manager.js ***!
  \***************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = function (oAppData) {
		var 
			App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 179),
			Promise = __webpack_require__(/*! bluebird */ 3)
		;
		
		if (App.getUserRole() === Enums.UserRole.SuperAdmin)
		{
			var
				_ = __webpack_require__(/*! underscore */ 2),
				
				TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43),
				
				Settings = __webpack_require__(/*! modules/AdminPanelWebclient/js/Settings.js */ 233),
				
				aAdminPanelTabsParams = []
			;

			Settings.init(oAppData);
			
			return {
				start: function () {
					aAdminPanelTabsParams.push({
						GetTabView: function(resolve) {
							__webpack_require__.e/* nsure */(1/*! admin-bundle */, function() {
									resolve(__webpack_require__(/*! modules/AdminPanelWebclient/js/views/DbAdminSettingsView.js */ 242));
								});
						},
						TabName: Settings.HashModuleName + '-db',
						TabTitle: TextUtils.i18n('ADMINPANELWEBCLIENT/LABEL_DB_SETTINGS_TAB')
					});
					aAdminPanelTabsParams.push({
						GetTabView: function(resolve) {
							__webpack_require__.e/* nsure */(1/*! admin-bundle */, function() {
									resolve(__webpack_require__(/*! modules/AdminPanelWebclient/js/views/SecurityAdminSettingsView.js */ 243));
								});
						},
						TabName: Settings.HashModuleName + '-security',
						TabTitle: TextUtils.i18n('ADMINPANELWEBCLIENT/LABEL_SECURITY_SETTINGS_TAB')
					});
					aAdminPanelTabsParams.push({
						GetTabView: function(resolve) {
							__webpack_require__.e/* nsure */(1/*! admin-bundle */, function() {
									resolve(__webpack_require__(/*! modules/AdminPanelWebclient/js/views/CommonSettingsPaneView.js */ 244));
								});
						},
						TabName: 'common',
						TabTitle: TextUtils.i18n('ADMINPANELWEBCLIENT/LABEL_COMMON_SETTINGS_TAB')
					});
					aAdminPanelTabsParams.push({
						GetTabView: function(resolve) {
							__webpack_require__.e/* nsure */(1/*! admin-bundle */, function() {
									resolve(__webpack_require__(/*! modules/AdminPanelWebclient/js/views/AboutAdminSettingsView.js */ 245));
								});
						},
						TabName: 'about',
						TabTitle: TextUtils.i18n('ADMINPANELWEBCLIENT/LABEL_ABOUT_SETTINGS_TAB')
					});
				},
				getScreens: function () {
					var oScreens = {};
					oScreens[Settings.HashModuleName] = function () {
						
						return new Promise(function(resolve, reject) {
							__webpack_require__.e/* nsure */(1/*! admin-bundle */, function(require) {
									var
										oSettingsView = __webpack_require__(/*! modules/AdminPanelWebclient/js/views/SettingsView.js */ 235),
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
								});
						});
					};
					return oScreens;
				},
				getAbstractSettingsFormViewClass: function () {
					return __webpack_require__(/*! modules/AdminPanelWebclient/js/views/CAbstractSettingsFormView.js */ 234);
				},
				registerAdminPanelTab: function (fGetTabView, sTabName, sTabTitle) {
					aAdminPanelTabsParams.push({
						GetTabView: fGetTabView,
						TabName: sTabName,
						TabTitle: sTabTitle
					});
				},
				setAddHash: function (aAddHash) {
					var SettingsView = __webpack_require__(/*! modules/AdminPanelWebclient/js/views/SettingsView.js */ 235);
					SettingsView.setAddHash(aAddHash);
				}
			};
		}
		
		return null;
	};


/***/ }),

/***/ 233:
/*!****************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/Settings.js ***!
  \****************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		
		Types = __webpack_require__(/*! modules/CoreWebclient/js/utils/Types.js */ 44)
	;

	module.exports = {
		ServerModuleName: 'AdminPanelWebclient',
		HashModuleName: 'admin',
		
		EntitiesData: [
	//		{
	//			Type: 'Tenant',
	//			ScreenHash: 'tenants',
	//			LinkTextKey: 'ADMINPANELWEBCLIENT/HEADING_TENANTS_SETTINGS_TABNAME'
	//		},
			{
				Type: 'User',
				ScreenHash: 'users',
				LinkTextKey: 'ADMINPANELWEBCLIENT/HEADING_USERS_SETTINGS_TABNAME'
			}
		],
		
		EntitiesPerPage: 20,
		TabsOrder: ['licensing', 'admin-security', 'admin-db', 'logs-viewer', 'system', 'common', 'modules', 'mail', 'mail-domains', 'mail-accounts', 'mail-servers', 'contacts', 'calendar', 'files', 'mobilesync', 'outlooksync', 'helpdesk', 'openpgp','about'],
		
		/**
		 * Initializes settings from AppData object sections.
		 * 
		 * @param {Object} oAppData Object contained modules settings.
		 */
		init: function (oAppData)
		{
			var oAppDataSection = oAppData['Core'];
			
			if (!_.isEmpty(oAppDataSection))
			{
				this.EntitiesPerPage = Types.pPositiveInt(oAppDataSection.EntitiesPerPage, this.EntitiesPerPage);
				this.TabsOrder = Types.pArray(oAppDataSection.TabsOrder, this.TabsOrder);
			}
		}
	};


/***/ }),

/***/ 234:
/*!***************************************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/views/CAbstractSettingsFormView.js ***!
  \***************************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		ko = __webpack_require__(/*! knockout */ 46),
		
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43),
		
		Ajax = __webpack_require__(/*! modules/CoreWebclient/js/Ajax.js */ 189),
		Api = __webpack_require__(/*! modules/CoreWebclient/js/Api.js */ 181),
		Screens = __webpack_require__(/*! modules/CoreWebclient/js/Screens.js */ 183),
		
		Popups = __webpack_require__(/*! modules/CoreWebclient/js/Popups.js */ 184),
		ConfirmPopup = __webpack_require__(/*! modules/CoreWebclient/js/popups/ConfirmPopup.js */ 185)
	;

	/**
	 * @constructor
	 * @param {string} sServerModule
	 */
	function CAbstractSettingsFormView(sServerModule)
	{
		this.sServerModule = sServerModule ? sServerModule : 'Core';
		
		this.isSaving = ko.observable(false);
		
		this.visible = ko.observable(true);
		
		this.sSavedState = '';
		
		this.bShown = false;
	}

	CAbstractSettingsFormView.prototype.ViewTemplate = ''; // should be overriden

	CAbstractSettingsFormView.prototype.onRoute = function (aParams)
	{
		this.bShown = true;
		this.revert();
		if (_.isFunction(this.onRouteChild))
		{
			this.onRouteChild(aParams);
		}
	};

	/**
	 * @param {Function} fAfterHideHandler
	 * @param {Function} fRevertRouting
	 */
	CAbstractSettingsFormView.prototype.hide = function (fAfterHideHandler, fRevertRouting)
	{
		if (this.getCurrentState() !== this.sSavedState) // if values have been changed
		{
			Popups.showPopup(ConfirmPopup, [TextUtils.i18n('COREWEBCLIENT/CONFIRM_DISCARD_CHANGES'), _.bind(function (bDiscard) {
				if (bDiscard)
				{
					this.bShown = false;
					fAfterHideHandler();
					this.revert();
				}
				else if (_.isFunction(fRevertRouting))
				{
					fRevertRouting();
				}
			}, this)]);
		}
		else
		{
			this.bShown = false;
			fAfterHideHandler();
		}
	};

	/**
	 * Returns an array with the values of editable fields.
	 * 
	 * Should be overriden.
	 * 
	 * @returns {Array}
	 */
	CAbstractSettingsFormView.prototype.getCurrentValues = function ()
	{
		return [];
	};

	/**
	 * @returns {String}
	 */
	CAbstractSettingsFormView.prototype.getCurrentState = function ()
	{
		var aState = this.getCurrentValues();
		
		return aState.join(':');
	};

	CAbstractSettingsFormView.prototype.updateSavedState = function()
	{
		this.sSavedState = this.getCurrentState();
	};

	/**
	 * Puts values from the global settings object to the editable fields.
	 * 
	 * Should be overriden.
	 */
	CAbstractSettingsFormView.prototype.revertGlobalValues = function ()
	{
		
	};

	CAbstractSettingsFormView.prototype.revert = function ()
	{
		this.revertGlobalValues();
		
		this.updateSavedState();
	};

	/**
	 * Gets values from the editable fields and prepares object for passing to the server and saving settings therein.
	 * 
	 * Should be overriden.
	 * 
	 * @returns {Object}
	 */
	CAbstractSettingsFormView.prototype.getParametersForSave = function ()
	{
		return {};
	};

	/**
	 * Sends a request to the server to save the settings.
	 */
	CAbstractSettingsFormView.prototype.save = function ()
	{
		if (!_.isFunction(this.validateBeforeSave) || this.validateBeforeSave())
		{
			this.isSaving(true);

			Ajax.send(this.sServerModule, 'UpdateSettings', this.getParametersForSave(), this.onResponse, this);
		}
	};

	/**
	 * Applies saved values of settings to the global settings object.
	 * 
	 * Should be overriden.
	 * 
	 * @param {Object} oParameters Object that have been obtained by getParameters function.
	 */
	CAbstractSettingsFormView.prototype.applySavedValues = function (oParameters)
	{
		
	};

	/**
	 * Parses the response from the server.
	 * If the settings are normally stored, then updates them in the global settings object. 
	 * Otherwise shows an error message.
	 * 
	 * @param {Object} oResponse
	 * @param {Object} oRequest
	 */
	CAbstractSettingsFormView.prototype.onResponse = function (oResponse, oRequest)
	{
		this.isSaving(false);

		if (!oResponse.Result)
		{
			Api.showErrorByCode(oResponse, TextUtils.i18n('COREWEBCLIENT/ERROR_SAVING_SETTINGS_FAILED'));
		}
		else
		{
			var oParameters = oRequest.Parameters;
			
			this.updateSavedState();

			this.applySavedValues(oParameters);
			
			Screens.showReport(TextUtils.i18n('COREWEBCLIENT/REPORT_SETTINGS_UPDATE_SUCCESS'));
		}
	};

	/**
	 * Should be overriden.
	 * 
	 * @param {string} sEntityType
	 * @param {int} iEntityId
	 */
	CAbstractSettingsFormView.prototype.setAccessLevel = function (sEntityType, iEntityId)
	{
	};

	module.exports = CAbstractSettingsFormView;


/***/ }),

/***/ 235:
/*!**************************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/views/SettingsView.js ***!
  \**************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		$ = __webpack_require__(/*! jquery */ 1),
		ko = __webpack_require__(/*! knockout */ 46),
		Promise = __webpack_require__(/*! bluebird */ 3),
		
		Text = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43),
		
		App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 179),
		Screens = __webpack_require__(/*! modules/CoreWebclient/js/Screens.js */ 183),
		Routing = __webpack_require__(/*! modules/CoreWebclient/js/Routing.js */ 191),
		CAbstractScreenView = __webpack_require__(/*! modules/CoreWebclient/js/views/CAbstractScreenView.js */ 194),
		
		Links = __webpack_require__(/*! modules/AdminPanelWebclient/js/utils/Links.js */ 236),
		
		Settings = __webpack_require__(/*! modules/AdminPanelWebclient/js/Settings.js */ 233),
		CEntitiesView = __webpack_require__(/*! modules/AdminPanelWebclient/js/views/CEntitiesView.js */ 237)
	;

	/**
	 * Constructor of admin panel settings view.
	 * 
	 * @constructor
	 */
	function CSettingsView()
	{
		CAbstractScreenView.call(this, 'AdminPanelWebclient');
		
		this.aScreens = [
			{
				sHash: Routing.buildHashFromArray(Links.get('')),
				sLinkText: Text.i18n('ADMINPANELWEBCLIENT/HEADING_SYSTEM_SETTINGS_TABNAME'),
				sType: '',
				oView: null
			}
		];
		_.each(Settings.EntitiesData, _.bind(function (oEntityData) {
			var
				oView = new CEntitiesView(oEntityData.Type),
				fChangeEntity = _.bind(function (sType, iEntityId, sTabName) {
					if (sTabName === 'create')
					{
						this.createEntity();
					}
					else if (sType === this.currentEntityType())
					{
						this.changeEntity(sType, iEntityId, sTabName || '');
					}
				}, this)
			;
			
			oView.setChangeEntityHandler(fChangeEntity);
			
			this.aScreens.push({
				sHash: Routing.buildHashFromArray(Links.get(oEntityData.Type)),
				sLinkText: Text.i18n(oEntityData.LinkTextKey),
				sType: oEntityData.Type,
				oView: oView
			});
		}, this));
		this.currentEntityType = ko.observable('');
		this.currentEntitiesId = ko.observable({});
		this.currentEntitiesView = ko.computed(function () {
			var
				sCurrType = this.currentEntityType(),
				oCurrEntitiesData = _.find(this.aScreens, function (oData) {
					return oData.sType === sCurrType;
				})
			;
			return oCurrEntitiesData ? oCurrEntitiesData.oView : null;
		}, this);
		this.currentEntitiesView.subscribe(function () {
			if (this.currentEntitiesView())
			{
				this.currentEntitiesView().onShow();
			}
		}, this);
		this.showModulesTabs = ko.computed(function () {
			return this.currentEntityType() === '' || this.currentEntitiesView().hasSelectedEntity();
		}, this);
		
		this.tabs = ko.observableArray([]);
		
		this.currentTab = ko.observable(null);
		
		this.aStartErrors = [];
		
		App.broadcastEvent('AdminPanelWebclient::ConstructView::after', {'Name': this.ViewConstructorName, 'View': this});
	}

	_.extendOwn(CSettingsView.prototype, CAbstractScreenView.prototype);

	CSettingsView.prototype.ViewTemplate = 'AdminPanelWebclient_SettingsView';
	CSettingsView.prototype.ViewConstructorName = 'CSettingsView';

	/**
	 * Registers admin panel tab.
	 * 
	 * @param {Function} fGetTabView Function that returns Promise which resolves into view model of the tab.
	 * @param {Object} oTabName Tab name.
	 * @param {Object} oTabTitle Tab title.
	 */
	CSettingsView.prototype.registerTab = function (fGetTabView, oTabName, oTabTitle)
	{
		if (_.isFunction(fGetTabView))
		{
			var aTabs = this.tabs;
			
			return new Promise(fGetTabView).then(function (oTabView) {
				aTabs.push({
					view: oTabView,
					name: oTabName,
					title: oTabTitle
				});
			});
		}
		return false;
	};

	/**
	 * Sorts tabs by some modules order list
	 */
	CSettingsView.prototype.sortRegisterTabs = function ()
	{
		this.tabs(_.sortBy(this.tabs(), function (oTab) {
			var iIndex = _.indexOf(Settings.TabsOrder, oTab.name);
			return iIndex !== -1 ? iIndex : Settings.TabsOrder.length;
		}));
	};

	/**
	 * Sets hash without creating entity.
	 */
	CSettingsView.prototype.cancelCreatingEntity = function ()
	{
		Routing.setHash(Links.get(this.currentEntityType(), {}, ''));
	};

	/**
	 * Sets hash for creating entity.
	 */
	CSettingsView.prototype.createEntity = function ()
	{
		Routing.setHash(Links.get(this.currentEntityType(), {}, 'create'));
	};

	/**
	 * Sets hash to route to screen with specified entity type and|or entity identifier and|or settings tab.
	 * 
	 * @param {string} sEntityName Entity type to display.
	 * @param {number} iEntityId Identifier of entity to display.
	 * @param {string} sTabName Name of settings tab to display.
	 */
	CSettingsView.prototype.changeEntity = function (sEntityName, iEntityId, sTabName)
	{
		var
			oEntitiesId = _.clone(this.currentEntitiesId()),
			bHasTab = !!_.find(this.tabs(), function (oTab) {
				return oTab.name === sTabName;
			}),
			sCurrTabName = this.currentTab() ? this.currentTab().name : ''
		;
		oEntitiesId[sEntityName] = iEntityId;
		Routing.setHash(Links.get(sEntityName, oEntitiesId, bHasTab ? sTabName : sCurrTabName));
	};

	/**
	 * Runs after knockout binding. Checks if settings tab have error to show on start and shows them.
	 */
	CSettingsView.prototype.onBind = function ()
	{
		_.each(this.tabs(), _.bind(function (oTab) {
			if (oTab.view && _.isFunction(oTab.view.getStartError))
			{
				var koError = oTab.view.getStartError();
				if (_.isFunction(koError))
				{
					koError.subscribe(function () {
						this.showStartError();
					}, this);
					this.aStartErrors.push(koError);
				}
			}
		}, this));
		
		this.showStartError();
	};

	CSettingsView.prototype.showStartError = function ()
	{
		var aErrors = [];
		
		_.each(this.aStartErrors, function (koError) {
			var sError = koError();
			if (sError !== '')
			{
				aErrors.push(sError);
			}
		});
		
		Screens.showError(aErrors.join('<br /><br />'), true);
	};

	/**
	 * Parses parameters from url hash, hides current admin panel tab if nessessary and after that finds a new one and shows it.
	 * 
	 * @param {Array} aParams Parameters from url hash.
	 */
	CSettingsView.prototype.onRoute = function (aParams)
	{
		var
			oParams = Links.parse(aParams),
			aTabParams = aParams.slice(1),
			bSameType = this.currentEntityType() === oParams.CurrentType,
			bSameId = this.currentEntitiesId()[oParams.CurrentType] === oParams.Entities[oParams.CurrentType],
			bSameTab = this.currentTab() && this.currentTab().name === oParams.Last,
			oCurrentTab = this.currentTab(),
			fAfterTabHide = _.bind(function () {
				this.showNewScreenView(oParams);
				this.showNewTabView(oParams.Last, aTabParams); // only after showing new entities view
			}, this),
			fAfterRefuseTabHide = _.bind(function () {
				if (oCurrentTab)
				{
					Routing.replaceHashDirectly(Links.get(this.currentEntityType(), this.currentEntitiesId(), this.currentTab() ? this.currentTab().name : ''));
				}
			}, this)
		;
		
		if (!bSameType || !bSameId || !bSameTab)
		{
			if (oCurrentTab && $.isFunction(oCurrentTab.view.hide))
			{
				oCurrentTab.view.hide(fAfterTabHide, fAfterRefuseTabHide);
			}
			else
			{
				fAfterTabHide();
			}
		}
		else if (oCurrentTab)
		{
			oCurrentTab.view.onRoute(aTabParams);
		}
	};

	/**
	 * Shows new screen view.
	 * 
	 * @param {Object} oParams Parameters with information about new screen.
	 */
	CSettingsView.prototype.showNewScreenView = function (oParams)
	{
		var
			oCurrentEntityData = _.find(this.aScreens, function (oData) {
				return oData.sType === oParams.CurrentType;
			})
		;
		
		this.currentEntityType(oParams.CurrentType);
		this.currentEntitiesId(oParams.Entities);

		if (oCurrentEntityData && oCurrentEntityData.oView)
		{
			if (oParams.Last === 'create')
			{
				oCurrentEntityData.oView.openCreateForm();
			}
			else
			{
				oCurrentEntityData.oView.cancelCreatingEntity();
			}
			oCurrentEntityData.oView.changeEntity(oParams.Entities[oParams.CurrentType]);
		}
	};

	/**
	 * Shows tab with specified tab name. Should be called only after calling showNewScreenView method.
	 * 
	 * @param {string} sNewTabName New tab name.
	 * @param {array} aTabParams
	 */
	CSettingsView.prototype.showNewTabView = function (sNewTabName, aTabParams)
	{
		// Sets access level to all tabs so they can correct their visibilities
		_.each(this.tabs(), _.bind(function (oTab) {
			if (oTab.view && _.isFunction(oTab.view.setAccessLevel))
			{
				oTab.view.setAccessLevel(this.currentEntityType(), this.currentEntitiesId()[this.currentEntityType()]);
			}
		}, this));
		
		// Finds tab with name from the url hash
		var oNewTab = _.find(this.tabs(), function (oTab) {
			return oTab.name === sNewTabName;
		});
		
		// If the tab wasn't found finds the first available visible tab
		if (!oNewTab || !(oNewTab.view && oNewTab.view.visible()))
		{
			oNewTab = _.find(this.tabs(), function (oTab) {
				return oTab.view && oTab.view.visible();
			});
		}
		
		// If tab was found calls its onRoute function and sets new current tab
		if (oNewTab)
		{
			if ($.isFunction(oNewTab.view.onRoute))
			{
				oNewTab.view.onRoute(aTabParams);
			}
			this.currentTab(oNewTab);
		}
	};

	/**
	 * Sets hash for showing another admin panel tab.
	 * 
	 * @param {string} sTabName Tab name.
	 */
	CSettingsView.prototype.changeTab = function (sTabName)
	{
		Routing.setHash(Links.get(this.currentEntityType(), this.currentEntitiesId(), sTabName));
	};

	/**
	 * Calls logout function of application.
	 */
	CSettingsView.prototype.logout = function ()
	{
		App.logout();
	};

	/**
	 * Deletes current entity.
	 */
	CSettingsView.prototype.deleteCurrentEntity = function ()
	{
		if (this.currentEntitiesView())
		{
			this.currentEntitiesView().deleteCurrentEntity();
		}
	};

	/**
	 * @param {Array} aAddHash
	 */
	CSettingsView.prototype.setAddHash = function (aAddHash)
	{
		Routing.setHash(_.union([Settings.HashModuleName, this.currentTab() ? this.currentTab().name : ''], aAddHash));
	};

	module.exports = new CSettingsView();


/***/ }),

/***/ 236:
/*!*******************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/utils/Links.js ***!
  \*******************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		
		Types = __webpack_require__(/*! modules/CoreWebclient/js/utils/Types.js */ 44),
		
		Settings = __webpack_require__(/*! modules/AdminPanelWebclient/js/Settings.js */ 233),
		
		sSrchPref = 's.',
		sPagePref = 'p.',
		
		Links = {}
	;

	/**
	 * Returns true if parameter contains path value.
	 * @param {string} sTemp
	 * @return {boolean}
	 */
	function IsPageParam(sTemp)
	{
		return (sPagePref === sTemp.substr(0, 1) && (/^[1-9][\d]*$/).test(sTemp.substr(sPagePref.length)));
	};

	/**
	 * Returns true if parameter contains search value.
	 * @param {string} sTemp
	 * @return {boolean}
	 */
	function IsSearchParam(sTemp)
	{
		return (sSrchPref === sTemp.substr(0, sSrchPref.length));
	};

	/**
	 * @param {Array=} aEntities
	 * @param {string=} sCurrEntityType = ''
	 * @param {string=} sLast = ''
	 * @param {number=} iPage = 1
	 * @param {string=} sSearch = ''
	 * @return {Array}
	 */
	Links.get = function (sCurrEntityType, aEntities, sLast, iPage, sSearch)
	{
		var aResult = [Settings.HashModuleName];
		
		aEntities = aEntities || [];
		
		_.each(Settings.EntitiesData, function (oEntityData) {
			if (Types.isPositiveNumber(aEntities[oEntityData.Type]))
			{
				aResult.push(oEntityData.ScreenHash.substr(0,1) + aEntities[oEntityData.Type]);
			}
			else if (sCurrEntityType === oEntityData.Type)
			{
				aResult.push(oEntityData.ScreenHash);
			}
		});
		
		if (Types.isPositiveNumber(iPage) && iPage > 1)
		{
			aResult.push(sPagePref + iPage);
		}
		
		if (Types.isNonEmptyString(sSearch))
		{
			aResult.push(sSrchPref + sSearch);
		}
		
		if (Types.isNonEmptyString(sLast))
		{
			aResult.push(sLast);
		}
		
		return aResult;
	};

	/**
	 * @param {Array} aParams
	 * 
	 * @return {Object}
	 */
	Links.parse = function (aParams)
	{
		var
			iIndex = 0,
			oEntities = {},
			sCurrEntityType = '',
			iPage = 1,
			sSearch = '',
			sTemp = ''
		;
		
		_.each(Settings.EntitiesData, function (oEntityData) {
			if (aParams[iIndex] && oEntityData.ScreenHash === aParams[iIndex])
			{
				sCurrEntityType = oEntityData.Type;
				iIndex++;
			}
			if (aParams[iIndex] && oEntityData.ScreenHash.substr(0, 1) === aParams[iIndex].substr(0, 1) && Types.pInt(aParams[iIndex].substr(1)) > 0)
			{
				oEntities[oEntityData.Type] = Types.pInt(aParams[iIndex].substr(1));
				sCurrEntityType = oEntityData.Type;
				iIndex++;
			}
			if (aParams.length > iIndex)
			{
				sTemp = Types.pString(aParams[iIndex]);
				if (IsPageParam(sTemp))
				{
					iPage = Types.pInt(sTemp.substr(sPagePref.length));
					if (iPage <= 0)
					{
						iPage = 1;
					}
					iIndex++;
				}
			}
			if (aParams.length > iIndex)
			{
				sTemp = Types.pString(aParams[iIndex]);
				if (IsSearchParam(sTemp))
				{
					sSearch = sTemp.substr(sSrchPref.length);
					iIndex++;
				}
			}
		});
		
		return {
			Entities: oEntities,
			CurrentType: sCurrEntityType,
			Last: Types.isNonEmptyString(aParams[iIndex]) ? aParams[iIndex] : '',
			Page: iPage,
			Search: sSearch
		};
	};

	module.exports = Links;


/***/ }),

/***/ 237:
/*!***************************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/views/CEntitiesView.js ***!
  \***************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		ko = __webpack_require__(/*! knockout */ 46),
		
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43),
		Types = __webpack_require__(/*! modules/CoreWebclient/js/utils/Types.js */ 44),
		Utils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Common.js */ 213),
		
		Api = __webpack_require__(/*! modules/CoreWebclient/js/Api.js */ 181),
		Screens = __webpack_require__(/*! modules/CoreWebclient/js/Screens.js */ 183),
		
		CPageSwitcherView = __webpack_require__(/*! modules/CoreWebclient/js/views/CPageSwitcherView.js */ 238),
		
		Popups = __webpack_require__(/*! modules/CoreWebclient/js/Popups.js */ 184),
		ConfirmPopup = __webpack_require__(/*! modules/CoreWebclient/js/popups/ConfirmPopup.js */ 185),
		
		Ajax = __webpack_require__(/*! modules/AdminPanelWebclient/js/Ajax.js */ 239),
		Settings = __webpack_require__(/*! modules/AdminPanelWebclient/js/Settings.js */ 233)
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
			return TextUtils.i18n('ADMINPANELWEBCLIENT/INFO_SEARCH_RESULT', {
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

	CEntitiesView.prototype.ViewTemplate = 'AdminPanelWebclient_EntitiesView';
	CEntitiesView.prototype.CreateFormViewTemplate = 'AdminPanelWebclient_EntityCreateFormView';

	/**
	 * Returns entity edit view for cpecified entity type.
	 */
	CEntitiesView.prototype.getEntityCreateView = function ()
	{
		switch (this.sType)
		{
			case 'Tenant':
				return __webpack_require__(/*! modules/AdminPanelWebclient/js/views/EditTenantView.js */ 240);
			case 'User':
				return __webpack_require__(/*! modules/AdminPanelWebclient/js/views/EditUserView.js */ 241);
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
					Screens.showReport(TextUtils.i18n('ADMINPANELWEBCLIENT/REPORT_CREATE_ENTITY_' + this.sType.toUpperCase()));
					this.justCreatedId(Types.pInt(oResponse.Result));
					this.cancelCreatingEntity();
				}
				else
				{
					Api.showErrorByCode(oResponse, TextUtils.i18n('ADMINPANELWEBCLIENT/ERROR_CREATE_ENTITY_' + this.sType.toUpperCase()));
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
				TextUtils.i18n('ADMINPANELWEBCLIENT/CONFIRM_DELETE_' + this.sType.toUpperCase() + '_PLURAL', {}, null, aIdList.length), 
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
					Screens.showReport(TextUtils.i18n('ADMINPANELWEBCLIENT/REPORT_DELETE_ENTITIES_' + this.sType.toUpperCase() + '_PLURAL', {}, null, aIdList.length));
				}
				else
				{
					Screens.showError(TextUtils.i18n('ADMINPANELWEBCLIENT/ERROR_DELETE_ENTITIES_' + this.sType.toUpperCase() + '_PLURAL', {}, null, aIdList.length));
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


/***/ }),

/***/ 238:
/*!*************************************************************!*\
  !*** ./modules/CoreWebclient/js/views/CPageSwitcherView.js ***!
  \*************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		$ = __webpack_require__(/*! jquery */ 1),
		ko = __webpack_require__(/*! knockout */ 46),
		
		Utils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Common.js */ 213),
		
		App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 179)
	;

	/**
	 * @constructor
	 * @param {number} iCount
	 * @param {number} iPerPage
	 */
	function CPageSwitcherView(iCount, iPerPage)
	{
		this.bShown = false;
		
		this.currentPage = ko.observable(1);
		this.count = ko.observable(iCount);
		this.perPage = ko.observable(iPerPage);
		this.firstPage = ko.observable(1);
		this.lastPage = ko.observable(1);

		this.pagesCount = ko.computed(function () {
			var iCount = this.perPage() > 0 ? Math.ceil(this.count() / this.perPage()) : 0;
			return (iCount > 0) ? iCount : 1;
		}, this);

		ko.computed(function () {

			var
				iAllLimit = 20,
				iLimit = 4,
				iPagesCount = this.pagesCount(),
				iCurrentPage = this.currentPage(),
				iStart = iCurrentPage,
				iEnd = iCurrentPage
			;

			if (iPagesCount > 1)
			{
				while (true)
				{
					iAllLimit--;
					
					if (1 < iStart)
					{
						iStart--;
						iLimit--;
					}

					if (0 === iLimit)
					{
						break;
					}

					if (iPagesCount > iEnd)
					{
						iEnd++;
						iLimit--;
					}

					if (0 === iLimit)
					{
						break;
					}

					if (0 === iAllLimit)
					{
						break;
					}
				}
			}

			this.firstPage(iStart);
			this.lastPage(iEnd);
			
		}, this);

		this.visibleFirst = ko.computed(function () {
			return (this.firstPage() > 1);
		}, this);

		this.visibleLast = ko.computed(function () {
			return (this.lastPage() < this.pagesCount());
		}, this);

		this.clickPage = _.bind(this.clickPage, this);

		this.pages = ko.computed(function () {
			var
				iIndex = this.firstPage(),
				aPages = []
			;

			if (this.firstPage() < this.lastPage())
			{
				for (; iIndex <= this.lastPage(); iIndex++)
				{
					aPages.push({
						number: iIndex,
						current: (iIndex === this.currentPage()),
						clickFunc: this.clickPage
					});
				}
			}

			return aPages;
		}, this);
		
		if (!App.isMobile())
		{
			this.hotKeysBind();
		}
	}

	CPageSwitcherView.prototype.ViewTemplate = 'CoreWebclient_PageSwitcherView';

	CPageSwitcherView.prototype.hotKeysBind = function ()
	{
		$(document).on('keydown', $.proxy(function(ev) {
			if (this.bShown && !Utils.isTextFieldFocused())
			{
				var sKey = ev.keyCode;
				if (ev.ctrlKey && sKey === Enums.Key.Left)
				{
					this.clickPreviousPage();
				}
				else if (ev.ctrlKey && sKey === Enums.Key.Right)
				{
					this.clickNextPage();
				}
			}
		},this));
	};

	CPageSwitcherView.prototype.hide = function ()
	{
		this.bShown = false;
	};

	CPageSwitcherView.prototype.show = function ()
	{
		this.bShown = true;
	};

	CPageSwitcherView.prototype.clear = function ()
	{
		this.currentPage(1);
		this.count(0);
	};

	/**
	 * @param {number} iCount
	 */
	CPageSwitcherView.prototype.setCount = function (iCount)
	{
		this.count(iCount);
		if (this.currentPage() > this.pagesCount())
		{
			this.currentPage(this.pagesCount());
		}
	};

	/**
	 * @param {number} iPage
	 * @param {number} iPerPage
	 */
	CPageSwitcherView.prototype.setPage = function (iPage, iPerPage)
	{
		this.perPage(iPerPage);
		if (iPage > this.pagesCount())
		{
			this.currentPage(this.pagesCount());
		}
		else
		{
			this.currentPage(iPage);
		}
	};

	/**
	 * @param {Object} oPage
	 */
	CPageSwitcherView.prototype.clickPage = function (oPage)
	{
		var iPage = oPage.number;
		if (iPage < 1)
		{
			iPage = 1;
		}
		if (iPage > this.pagesCount())
		{
			iPage = this.pagesCount();
		}
		this.currentPage(iPage);
	};

	CPageSwitcherView.prototype.clickFirstPage = function ()
	{
		this.currentPage(1);
	};

	CPageSwitcherView.prototype.clickPreviousPage = function ()
	{
		var iPrevPage = this.currentPage() - 1;
		if (iPrevPage < 1)
		{
			iPrevPage = 1;
		}
		this.currentPage(iPrevPage);
	};

	CPageSwitcherView.prototype.clickNextPage = function ()
	{
		var iNextPage = this.currentPage() + 1;
		if (iNextPage > this.pagesCount())
		{
			iNextPage = this.pagesCount();
		}
		this.currentPage(iNextPage);
	};

	CPageSwitcherView.prototype.clickLastPage = function ()
	{
		this.currentPage(this.pagesCount());
	};

	module.exports = CPageSwitcherView;


/***/ }),

/***/ 239:
/*!************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/Ajax.js ***!
  \************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		Ajax = __webpack_require__(/*! modules/CoreWebclient/js/Ajax.js */ 189),
		
		Settings = __webpack_require__(/*! modules/AdminPanelWebclient/js/Settings.js */ 233)
	;

	module.exports = {
		send: function (sMethod, oParameters, fResponseHandler, oContext) {
			Ajax.send(Settings.ServerModuleName, sMethod, oParameters, fResponseHandler, oContext);
		}
	};

/***/ }),

/***/ 240:
/*!****************************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/views/EditTenantView.js ***!
  \****************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		ko = __webpack_require__(/*! knockout */ 46),
		
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43)
	;

	/**
	 * @constructor
	 */
	function CEditTenantView()
	{
		this.sHeading = TextUtils.i18n('ADMINPANELWEBCLIENT/HEADING_CREATE_TENANT');
		this.id = ko.observable(0);
		this.name = ko.observable('');
		this.description = ko.observable('');
	}

	CEditTenantView.prototype.ViewTemplate = 'AdminPanelWebclient_EditTenantView';

	CEditTenantView.prototype.getCurrentValues = function ()
	{
		return [
			this.id(),
			this.name(),
			this.description()
		];
	};

	CEditTenantView.prototype.clearFields = function ()
	{
		this.id(0);
		this.name('');
		this.description('');
	};

	CEditTenantView.prototype.parse = function (iEntityId, oResult)
	{
		if (oResult)
		{
			this.id(iEntityId);
			this.name(oResult.Name);
			this.description(oResult.Description);
		}
		else
		{
			this.clearFields();
		}
	};

	CEditTenantView.prototype.getParametersForSave = function ()
	{
		return {
			Id: this.id(),
			Name: this.name(),
			Description: this.description()
		};
	};

	module.exports = new CEditTenantView();


/***/ }),

/***/ 241:
/*!**************************************************************!*\
  !*** ./modules/AdminPanelWebclient/js/views/EditUserView.js ***!
  \**************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		$ = __webpack_require__(/*! jquery */ 1),
		ko = __webpack_require__(/*! knockout */ 46),
		
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43),
		
		Screens = __webpack_require__(/*! modules/CoreWebclient/js/Screens.js */ 183),
		App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 179)
	;

	/**
	 * @constructor
	 */
	function CEditUserView()
	{
		this.sHeading = TextUtils.i18n('ADMINPANELWEBCLIENT/HEADING_CREATE_USER');
		this.id = ko.observable(0);
		this.publicId = ko.observable('');
		this.aRoles = [
			{text: TextUtils.i18n('ADMINPANELWEBCLIENT/LABEL_ADMINISTRATOR'), value: Enums.UserRole.SuperAdmin},
			{text: TextUtils.i18n('ADMINPANELWEBCLIENT/LABEL_USER'), value: Enums.UserRole.NormalUser},
			{text: TextUtils.i18n('ADMINPANELWEBCLIENT/LABEL_GUEST'), value: Enums.UserRole.Customer}
		];
		this.role = ko.observable(Enums.UserRole.NormalUser);
		this.writeSeparateLog = ko.observable(false);
		
		App.broadcastEvent('AdminPanelWebclient::ConstructView::after', {'Name': this.ViewConstructorName, 'View': this});
	}

	CEditUserView.prototype.ViewTemplate = 'AdminPanelWebclient_EditUserView';
	CEditUserView.prototype.ViewConstructorName = 'CEditUserView';

	CEditUserView.prototype.getCurrentValues = function ()
	{
		return [
			this.id(),
			this.publicId(),
			this.role(),
			this.writeSeparateLog()
		];
	};

	CEditUserView.prototype.clearFields = function ()
	{
		this.id(0);
		this.publicId('');
		this.role(Enums.UserRole.NormalUser);
		this.writeSeparateLog(false);
	};

	CEditUserView.prototype.parse = function (iEntityId, oResult)
	{
		if (oResult)
		{
			this.id(iEntityId);
			this.publicId(oResult.PublicId);
			this.role(oResult.Role);
			this.writeSeparateLog(!!oResult.WriteSeparateLog);
		}
		else
		{
			this.clearFields();
		}
	};

	CEditUserView.prototype.isValidSaveData = function ()
	{
		var bValid = $.trim(this.publicId()) !== '';
		if (!bValid)
		{
			Screens.showError(TextUtils.i18n('ADMINPANELWEBCLIENT/ERROR_USER_NAME_EMPTY'));
		}
		return bValid;
	};

	CEditUserView.prototype.getParametersForSave = function ()
	{
		return {
			Id: this.id(),
			PublicId: $.trim(this.publicId()),
			Role: this.role(),
			WriteSeparateLog: this.writeSeparateLog()
		};
	};

	CEditUserView.prototype.saveEntity = function (aParents, oRoot)
	{
		_.each(aParents, function (oParent) {
			if (oParent.constructor.name === 'CEntitiesView' && _.isFunction(oParent.createEntity))
			{
				oParent.createEntity();
			}
			if (oParent.constructor.name === 'CCommonSettingsPaneView' && _.isFunction(oParent.save))
			{
				oParent.save(oRoot);
			}
		});
	};

	module.exports = new CEditUserView();


/***/ })

});