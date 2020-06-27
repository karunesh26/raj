webpackJsonp([8],{

/***/ 290:
/*!***************************************************!*\
  !*** ./modules/LogsViewerWebclient/js/manager.js ***!
  \***************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = function (oAppData) {
		var App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 179);
		
		if (App.getUserRole() === Enums.UserRole.SuperAdmin)
		{
			var
				TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43),
				
				Settings = __webpack_require__(/*! modules/LogsViewerWebclient/js/Settings.js */ 291)
			;

			Settings.init(oAppData);

			__webpack_require__(/*! modules/LogsViewerWebclient/js/enums.js */ 292);

			return {
				start: function (ModulesManager) {
					ModulesManager.run('AdminPanelWebclient', 'registerAdminPanelTab', [
						function(resolve) {
							__webpack_require__.e/* nsure */(1/*! admin-bundle */, function() {
									resolve(__webpack_require__(/*! modules/LogsViewerWebclient/js/views/AdminSettingsView.js */ 293));
								});
						},
						Settings.HashModuleName,
						TextUtils.i18n('LOGSVIEWERWEBCLIENT/LABEL_LOGGING_SETTINGS_TAB')
					]);
				}
			};
		}
		
		return null;
	};


/***/ }),

/***/ 291:
/*!****************************************************!*\
  !*** ./modules/LogsViewerWebclient/js/Settings.js ***!
  \****************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		
		Types = __webpack_require__(/*! modules/CoreWebclient/js/utils/Types.js */ 44)
	;

	module.exports = {
		ServerModuleName: 'LogsViewerWebclient',
		HashModuleName: 'logs-viewer',
		
		EnableLogging: false,
		EnableEventLogging: false,
		LoggingLevel: 0,
		LogSizeBytes: 0,
		EventLogSizeBytes: 0,
		LogFileName: '',
		EventLogFileName: '',
		
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
				this.ELogLevel = Types.pObject(oAppDataSection.ELogLevel);
				
				this.EnableLogging = Types.pBool(oAppDataSection.EnableLogging);
				this.EnableEventLogging = Types.pBool(oAppDataSection.EnableEventLogging);
				this.LoggingLevel = Types.pEnum(oAppDataSection.LoggingLevel, this.ELogLevel, this.LoggingLevel);
				this.updateLogsData(Types.pObject(oAppDataSection.LogFilesData));
			}
		},
		
		/**
		 * Updates new settings values after saving on server.
		 * 
		 * @param {boolean} bEnableLogging
		 * @param {boolean} bEnableEventLogging
		 * @param {number} iLoggingLevel
		 */
		updateLogging: function (bEnableLogging, bEnableEventLogging, iLoggingLevel)
		{
			this.EnableLogging = !!bEnableLogging;
			this.EnableEventLogging = !!bEnableEventLogging;
			this.LoggingLevel = Types.pInt(iLoggingLevel);
		},
		
		/**
		 * Updates new settings values after requesting from server.
		 * 
		 * @param {Object} oLogFilesData
		 */
		updateLogsData: function (oLogFilesData)
		{
			this.LogSizeBytes = Types.pInt(oLogFilesData.LogSizeBytes);
			this.EventLogSizeBytes = Types.pInt(oLogFilesData.EventLogSizeBytes);
			this.LogFileName = Types.pString(oLogFilesData.LogFileName);
			this.EventLogFileName = Types.pString(oLogFilesData.EventLogFileName);
		}
	};


/***/ }),

/***/ 292:
/*!*************************************************!*\
  !*** ./modules/LogsViewerWebclient/js/enums.js ***!
  \*************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		
		Settings = __webpack_require__(/*! modules/LogsViewerWebclient/js/Settings.js */ 291),
		
		Enums = {}
	;

	Enums.LogLevel = Settings.ELogLevel;

	if (typeof window.Enums === 'undefined')
	{
		window.Enums = {};
	}

	_.extendOwn(window.Enums, Enums);


/***/ })

});