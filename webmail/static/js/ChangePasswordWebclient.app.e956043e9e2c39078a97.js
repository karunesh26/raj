webpackJsonp([3],{

/***/ 246:
/*!*******************************************************!*\
  !*** ./modules/ChangePasswordWebclient/js/manager.js ***!
  \*******************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = function (oAppData) {
		var App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 179);
		
		if (App.getUserRole() === Enums.UserRole.NormalUser)
		{
			var Settings = __webpack_require__(/*! modules/ChangePasswordWebclient/js/Settings.js */ 247);

			Settings.init(oAppData);

			return {
				start: function (ModulesManager) {
					ModulesManager.run(
						'SettingsWebclient',
						'registerSettingsTabSection', 
						[
							function () { return __webpack_require__(/*! modules/ChangePasswordWebclient/js/views/ChangeSingleMailAccountPasswordView.js */ 248); },
							'common',
							'common'
						]
					);
				},
				getChangePasswordPopup: function () {
					return __webpack_require__(/*! modules/ChangePasswordWebclient/js/popups/ChangePasswordPopup.js */ 249);
				}
			};
		}
		
		return null;
	};


/***/ }),

/***/ 247:
/*!********************************************************!*\
  !*** ./modules/ChangePasswordWebclient/js/Settings.js ***!
  \********************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		
		Types = __webpack_require__(/*! modules/CoreWebclient/js/utils/Types.js */ 44)
	;

	module.exports = {
		PasswordMinLength: 0,
		PasswordMustBeComplex: false,
		
		/**
		 * Initializes settings from AppData object sections.
		 * 
		 * @param {Object} oAppData Object contained modules settings.
		 */
		init: function (oAppData)
		{
			var
				oAppDataSection = oAppData['ChangePasswordWebclient'],
				oAppDataMailSection = oAppData['Mail']
			;
			
			if (!_.isEmpty(oAppDataSection))
			{
				this.PasswordMinLength = Types.pNonNegativeInt(oAppDataSection.PasswordMinLength, this.PasswordMinLength);
				this.PasswordMustBeComplex = Types.pBool(oAppDataSection.PasswordMustBeComplex, this.PasswordMustBeComplex);
			}
			
			if (!_.isEmpty(oAppDataMailSection))
			{
				this.MailAllowAddAccounts = oAppDataMailSection.AllowAddAccounts;
				this.MailAllowMultiAccounts = oAppDataMailSection.AllowMultiAccounts;
			}
		}
	};


/***/ }),

/***/ 248:
/*!*****************************************************************************************!*\
  !*** ./modules/ChangePasswordWebclient/js/views/ChangeSingleMailAccountPasswordView.js ***!
  \*****************************************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		ko = __webpack_require__(/*! knockout */ 46),
		
		ModulesManager = __webpack_require__(/*! modules/CoreWebclient/js/ModulesManager.js */ 42),
		
		Popups = __webpack_require__(/*! modules/CoreWebclient/js/Popups.js */ 184),
		ChangePasswordPopup = ModulesManager.run('ChangePasswordWebclient', 'getChangePasswordPopup'),
		
		Settings = __webpack_require__(/*! modules/ChangePasswordWebclient/js/Settings.js */ 247)
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

	CChangeSingleMailAccountPasswordView.prototype.ViewTemplate = 'ChangePasswordWebclient_ChangeSingleMailAccountPasswordView';

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


/***/ }),

/***/ 249:
/*!**************************************************************************!*\
  !*** ./modules/ChangePasswordWebclient/js/popups/ChangePasswordPopup.js ***!
  \**************************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		$ = __webpack_require__(/*! jquery */ 1),
		ko = __webpack_require__(/*! knockout */ 46),
		
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43),
		
		Ajax = __webpack_require__(/*! modules/CoreWebclient/js/Ajax.js */ 189),
		Api = __webpack_require__(/*! modules/CoreWebclient/js/Api.js */ 181),
		Screens = __webpack_require__(/*! modules/CoreWebclient/js/Screens.js */ 183),
		
		CAbstractPopup = __webpack_require__(/*! modules/CoreWebclient/js/popups/CAbstractPopup.js */ 186),
		
		Settings = __webpack_require__(/*! modules/ChangePasswordWebclient/js/Settings.js */ 247)
	;

	/**
	 * @constructor
	 */
	function CChangePasswordPopup()
	{
		CAbstractPopup.call(this);
		
		this.currentPassword = ko.observable('');
		this.newPassword = ko.observable('');
		this.confirmPassword = ko.observable('');
		
		this.accountId = ko.observable('');
		this.hasOldPassword = ko.observable(false);
		this.oParams = null;
	}

	_.extendOwn(CChangePasswordPopup.prototype, CAbstractPopup.prototype);

	CChangePasswordPopup.prototype.PopupTemplate = 'ChangePasswordWebclient_ChangePasswordPopup';

	/**
	 * @param {Object} oParams
	 * @param {String} oParams.sModule
	 * @param {boolean} oParams.bHasOldPassword
	 * @param {Function} oParams.fAfterPasswordChanged
	 */
	CChangePasswordPopup.prototype.onOpen = function (oParams)
	{
		this.currentPassword('');
		this.newPassword('');
		this.confirmPassword('');
		
		this.accountId(oParams.iAccountId);
		this.hasOldPassword(oParams.bHasOldPassword);
		this.oParams = oParams;
	};

	CChangePasswordPopup.prototype.change = function ()
	{
		var
			sNewPass = $.trim(this.newPassword()),
			sConfirmPassword = $.trim(this.confirmPassword())
		;
		if (sConfirmPassword !== sNewPass)
		{
			Screens.showError(TextUtils.i18n('COREWEBCLIENT/ERROR_PASSWORDS_DO_NOT_MATCH'));
		}
		else if (Settings.PasswordMinLength > 0 && sNewPass.length < Settings.PasswordMinLength) 
		{ 
			Screens.showError(TextUtils.i18n('CHANGEPASSWORDWEBCLIENT/ERROR_PASSWORD_TOO_SHORT').replace('%N%', Settings.PasswordMinLength));
		}
		else if (Settings.PasswordMustBeComplex && (!sNewPass.match(/([0-9])/) || !sNewPass.match(/([!,%,&,@,#,$,^,*,?,_,~])/)))
		{
			Screens.showError(TextUtils.i18n('CHANGEPASSWORDWEBCLIENT/ERROR_PASSWORD_TOO_SIMPLE'));
		}
		else
		{
			this.sendChangeRequest();
		}
	};

	CChangePasswordPopup.prototype.sendChangeRequest = function ()
	{
		var oParameters = {
			'AccountId': this.accountId(),
			'CurrentPassword': $.trim(this.currentPassword()),
			'NewPassword': $.trim(this.newPassword())
		};

		Ajax.send(this.oParams.sModule, 'ChangePassword', oParameters, this.onUpdatePasswordResponse, this);
	};

	/**
	 * @param {Object} oResponse
	 * @param {Object} oRequest
	 */
	CChangePasswordPopup.prototype.onUpdatePasswordResponse = function (oResponse, oRequest)
	{
		if (oResponse.Result === false)
		{
			Api.showErrorByCode(oResponse, TextUtils.i18n('CHANGEPASSWORDWEBCLIENT/ERROR_PASSWORD_NOT_SAVED'));
		}
		else
		{
			if (this.hasOldPassword())
			{
				Screens.showReport(TextUtils.i18n('CHANGEPASSWORDWEBCLIENT/REPORT_PASSWORD_CHANGED'));
			}
			else
			{
				Screens.showReport(TextUtils.i18n('CHANGEPASSWORDWEBCLIENT/REPORT_PASSWORD_SET'));
			}
			
			this.closePopup();
			
			if ($.isFunction(this.oParams.fAfterPasswordChanged))
			{
				this.oParams.fAfterPasswordChanged();
			}
		}
	};

	module.exports = new CChangePasswordPopup();


/***/ })

});