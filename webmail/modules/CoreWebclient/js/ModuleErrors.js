'use strict';

var
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	
	oErrors = Types.pObject(window.auroraAppData && Types.pObject(window.auroraAppData.module_errors), {})
;

module.exports = {
	getErrorMessage: function (oResponse)
	{
		var
			mResult = false,
			sMedResult = '',
			iErrorCode = typeof oResponse.ErrorCode !== 'undefined' ? oResponse.ErrorCode : null,
			sModuleName = typeof oResponse.Module !== 'undefined' ? oResponse.Module : null
		;
		
		if (iErrorCode !== null && sModuleName !== null
			&& typeof oErrors[sModuleName] !== 'undefined' 
			&& typeof oErrors[sModuleName][iErrorCode] !== 'undefined')
		{
			mResult = oErrors[sModuleName][iErrorCode];
		}
		
		if (Types.isNonEmptyString(mResult))
		{
			sMedResult = mResult.replace(/[^%]*%(\w+)%[^%]*/g, function(sMatch, sFound, iIndex, sStr) {
				if (Types.isNonEmptyString(oResponse[sFound]))
				{
					return sMatch.replace('%' + sFound + '%', oResponse[sFound]);
				}
				return sMatch;
			});
			if (Types.isNonEmptyString(sMedResult))
			{
				mResult = sMedResult;
			}
		}
		
		return mResult;
	}
};
