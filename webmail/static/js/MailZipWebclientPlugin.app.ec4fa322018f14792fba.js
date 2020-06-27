webpackJsonp([15],{

/***/ 375:
/*!******************************************************!*\
  !*** ./modules/MailZipWebclientPlugin/js/manager.js ***!
  \******************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = function (oAppData) {
		var App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 179);
		
		if (App.getUserRole() === Enums.UserRole.NormalUser)
		{
			var
				_ = __webpack_require__(/*! underscore */ 2),
				ko = __webpack_require__(/*! knockout */ 46),
				
				TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 43),
				Types = __webpack_require__(/*! modules/CoreWebclient/js/utils/Types.js */ 44),
				UrlUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Url.js */ 177),
				
				Ajax = __webpack_require__(/*! modules/CoreWebclient/js/Ajax.js */ 189),
				Api = __webpack_require__(/*! modules/CoreWebclient/js/Api.js */ 181),
				Screens = __webpack_require__(/*! modules/CoreWebclient/js/Screens.js */ 183),
				
				bAllowZip = oAppData['MailZipWebclientPlugin'] ? !!oAppData['MailZipWebclientPlugin'].AllowZip : false
			;
			
			return {
				start: function (ModulesManager) {
					if (bAllowZip)
					{
						App.subscribeEvent('MailWebclient::ParseFile::after', function (oFile) {
							if (oFile && _.isFunction(oFile.addAction) && oFile.extension() === 'zip')
							{
								oFile.mailzipSubFilesLoaded = ko.observable(false);
								oFile.mailzipSubFilesLoading = ko.observable(false);
								oFile.mailzipExpandFile = function ()
								{
									if (!this.mailzipSubFilesLoaded() && !this.mailzipSubFilesLoading())
									{
										this.mailzipSubFilesLoading(true);
										Ajax.send('MailZipWebclientPlugin', 'ExpandFile', { 'Hash': this.hash() }, function (oResponse) {
											this.mailzipSubFilesLoading(false);
											if (oResponse.Result)
											{
												this.subFiles([]);
												if (Types.isNonEmptyArray(oResponse.Result))
												{
													_.each(oResponse.Result, _.bind(function (oRawFile) {
														var oSubFile = oFile.getNewInstance();
														oSubFile.parse(oRawFile);
														this.subFiles.push(oSubFile);
													}, this));
												}
												this.mailzipSubFilesLoaded(true);
												this.subFilesExpanded(true);
											}
										}, this);
									}
									else
									{
										this.subFilesExpanded(true);
									}
								};
								
								var oActionData = {
									'Text': ko.computed(function () {
										if (this.subFilesExpanded())
										{
											return TextUtils.i18n('COREWEBCLIENT/ACTION_COLLAPSE_FILE');
										}
										if (this.mailzipSubFilesLoading())
										{
											return TextUtils.i18n('COREWEBCLIENT/INFO_LOADING');
										}
										return TextUtils.i18n('COREWEBCLIENT/ACTION_EXPAND_FILE');
									}, oFile),
									'Handler': _.bind(function () {
										if (!this.mailzipSubFilesLoading())
										{
											if (this.subFilesExpanded())
											{
												this.subFilesExpanded(false);
											}
											else
											{
												this.mailzipExpandFile();
											}
										}
									}, oFile)
								};
								
								oFile.addAction('expand', true, oActionData);
								oFile.removeAction('view');
							}
						});
						
						App.subscribeEvent('MailWebclient::AddAllAttachmentsDownloadMethod', function (fAddAllAttachmentsDownloadMethod) {
							fAddAllAttachmentsDownloadMethod({
								'Text': TextUtils.i18n('MAILZIPWEBCLIENTPLUGIN/ACTION_DOWNLOAD_ATTACHMENTS_ZIP'),
								'Handler': function (iAccountId, aHashes) {
									Screens.showLoading(TextUtils.i18n('COREWEBCLIENT/INFO_LOADING'));
									Ajax.send('MailZipWebclientPlugin', 'SaveAttachments', {
										'AccountID': iAccountId,
										'Attachments': aHashes
									}, function (oResponse) {
										Screens.hideLoading();
										if (oResponse.Result && oResponse.Result.Actions && oResponse.Result.Actions.download)
										{
											var sDownloadLink = oResponse.Result.Actions.download.url;
											UrlUtils.downloadByUrl(sDownloadLink);
										}
										else
										{
											Api.showErrorByCode(oResponse);
										}
									});
								}
							});
						});
					}
				}
			};
		}
		
		return null;
	};


/***/ })

});