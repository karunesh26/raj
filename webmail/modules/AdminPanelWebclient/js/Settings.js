'use strict';

var
	_ = require('underscore'),
	
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js')
;

module.exports = {
	ServerModuleName: 'AdminPanelWebclient',
	HashModuleName: 'admin',
	
	EntitiesData: [
//		{
//			Type: 'Tenant',
//			ScreenHash: 'tenants',
//			LinkTextKey: '%MODULENAME%/HEADING_TENANTS_SETTINGS_TABNAME'
//		},
		{
			Type: 'User',
			ScreenHash: 'users',
			LinkTextKey: '%MODULENAME%/HEADING_USERS_SETTINGS_TABNAME'
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
