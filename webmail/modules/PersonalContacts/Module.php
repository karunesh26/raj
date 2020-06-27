<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 * 
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 */

namespace Aurora\Modules\PersonalContacts;

/**
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractModule
{
	public function init() 
	{
		$this->subscribeEvent('Contacts::GetStorage', array($this, 'onGetStorage'));
		$this->subscribeEvent('Core::DeleteUser::before', array($this, 'onBeforeDeleteUser'));
		$this->subscribeEvent('Contacts::CreateContact::before', array($this, 'onBeforeCreateContact'));
		$this->subscribeEvent('Contacts::GetContacts::before', array($this, 'prepareFiltersFromStorage'));
		$this->subscribeEvent('Contacts::Export::before', array($this, 'prepareFiltersFromStorage'));
		$this->subscribeEvent('Contacts::GetContactsByEmails::before', array($this, 'prepareFiltersFromStorage'));
	}
	
	public function onGetStorage(&$aStorages)
	{
		$aStorages[] = 'personal';
	}
	
	public function onBeforeDeleteUser(&$aArgs, &$mResult)
	{
		$oContactsDecorator = \Aurora\Modules\Contacts\Module::Decorator();
		if ($oContactsDecorator)
		{
			$aFilters = [
				'$AND' => [
					'IdUser' => [$aArgs['UserId'], '='],
					'Storage' => ['personal', '=']
				]
			];
			$oApiContactsManager = $oContactsDecorator->GetApiContactsManager();
			$aUserContacts = $oApiContactsManager->getContacts(\Aurora\Modules\Contacts\Enums\SortField::Name, \Aurora\System\Enums\SortOrder::ASC, 0, 0, $aFilters, '');
			if (count($aUserContacts) > 0)
			{
				$aContactUUIDs = [];
				foreach ($aUserContacts as $oContact)
				{
					$aContactUUIDs[] = $oContact->UUID;
				}
				$oContactsDecorator->DeleteContacts($aContactUUIDs);
			}
		}
	}
	
	public function onBeforeCreateContact(&$aArgs, &$mResult)
	{
		if (isset($aArgs['Contact']))
		{
			if (!isset($aArgs['Contact']['Storage']) || $aArgs['Contact']['Storage'] === '')
			{
				$aArgs['Contact']['Storage'] = 'personal';
			}
		}
	}
	
	public function prepareFiltersFromStorage(&$aArgs, &$mResult)
	{
		if (isset($aArgs['Storage']) && ($aArgs['Storage'] === 'personal' || $aArgs['Storage'] === 'all'))
		{
			$iUserId = \Aurora\System\Api::getAuthenticatedUserId();
			if (!isset($aArgs['Filters']) || !\is_array($aArgs['Filters']))
			{
				$aArgs['Filters'] = array();
			}
			
			if (isset($aArgs['SortField']) && $aArgs['SortField'] === \Aurora\Modules\Contacts\Enums\SortField::Frequency)
			{
				$aArgs['Filters'][]['$AND'] = [
					'IdUser' => [$iUserId, '='],
					'Storage' => ['personal', '='],
					'Frequency' => [-1, '!='],
				];
			}
			else
			{
				$aArgs['Filters'][]['$AND'] = [
					'IdUser' => [$iUserId, '='],
					'Storage' => ['personal', '='],
					'$OR' => [
						'1@Auto' => [false, '='],
						'2@Auto' => ['NULL', 'IS']
					]
				];
			}
		}
	}
}
