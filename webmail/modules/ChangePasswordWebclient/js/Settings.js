'use strict';

var
	_ = require('underscore'),
	
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js')
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
			oAppDataSection = oAppData['%ModuleName%'],
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
