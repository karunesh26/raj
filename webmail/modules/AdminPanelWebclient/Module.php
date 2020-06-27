<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\AdminPanelWebclient;

/**
 * This module adds ability to login to the admin panel as a Super Administrator.
 *
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 *
 * @package Modules
 * @internal
 */
class Module extends \Aurora\System\Module\AbstractWebclientModule
{
	public function TestDbConnection($DbLogin, $DbName, $DbHost, $DbPassword = null)
	{
		return \Aurora\Modules\Core\Module::Decorator()->TestDbConnection($DbLogin, $DbName, $DbHost, $DbPassword);
	}
	
	public function CreateTables()
	{
		return \Aurora\Modules\Core\Module::Decorator()->CreateTables();
	}
	
	public function GetEntityList($Type, $Offset = 0, $Limit = 0, $Search = '')
	{
		return \Aurora\Modules\Core\Module::Decorator()->GetEntityList($Type, $Offset, $Limit, $Search);
	}
	
	public function GetEntity($Type, $Id)
	{
		return \Aurora\Modules\Core\Module::Decorator()->GetEntity($Type, $Id);
	}
	
	public function CreateTenant($ChannelId = 0, $Name = '', $Description = '')
	{
		return \Aurora\Modules\Core\Module::Decorator()->CreateTenant($ChannelId, $Name, $Description);
	}
	
	public function CreateUser($TenantId = 0, $PublicId = '', $Role = \Aurora\System\Enums\UserRole::NormalUser, $WriteSeparateLog = false)
	{
		return \Aurora\Modules\Core\Module::Decorator()->CreateUser($TenantId, $PublicId, $Role, $WriteSeparateLog);
	}
	
	/**
	 * @api {post} ?/Api/ UpdateEntity
	 * @apiName UpdateEntity
	 * @apiGroup Core
	 * @apiDescription Updates entity.
	 * 
	 * @apiHeader {string} Authorization "Bearer " + Authentication token which was received as the result of Core.Login method.
	 * @apiHeaderExample {json} Header-Example:
	 *	{
	 *		"Authorization": "Bearer 32b2ecd4a4016fedc4abee880425b6b8"
	 *	}
	 * 
	 * @apiParam {string=Core} Module Module name.
	 * @apiParam {string=UpdateEntity} Method Method name.
	 * @apiParam {string} Parameters JSON.stringified object <br>
	 * {<br>
	 * &emsp; **Type** *string* Entity type.<br>
	 * &emsp; **Data** *array* Entity data.<br>
	 * }
	 * 
	 * @apiParamExample {json} Request-Example:
	 * {
	 *	Module: 'Core',
	 *	Method: 'UpdateEntity',
	 *	Parameters: '{ Type: "Tenant", Data: { Id: 123, PublicId: "PublicId_value",
	 *		Description: "description_value" } }'
	 * }
	 * 
	 * @apiParamExample {json} Request-Example:
	 * {
	 *	Module: 'Core',
	 *	Method: 'UpdateEntity',
	 *	Parameters: '{ Type: "User", Data: { Id: 123, PublicId: "PublicId_value", Role: 2 } }'
	 * }
	 * 
	 * @apiSuccess {object[]} Result Array of response objects.
	 * @apiSuccess {string} Result.Module Module name.
	 * @apiSuccess {string} Result.Method Method name.
	 * @apiSuccess {bool} Result.Result Indicates if entity was updated successfully.
	 * @apiSuccess {int} [Result.ErrorCode] Error code.
	 * 
	 * @apiSuccessExample {json} Success response example:
	 * {
	 *	Module: 'Core',
	 *	Method: 'UpdateEntity',
	 *	Result: true
	 * }
	 * 
	 * @apiSuccessExample {json} Error response example:
	 * {
	 *	Module: 'Core',
	 *	Method: 'UpdateEntity',
	 *	Result: false,
	 *	ErrorCode: 102
	 * }
	 */
	/**
	 * Updates entity.
	 * 
	 * @param string $Type Entity type.
	 * @param array $Data Entity data.
	 * @return bool
	 */
	public function UpdateEntity($Type, $Data)
	{
		switch ($Type)
		{
			case 'Tenant':
				return \Aurora\Modules\Core\Module::Decorator()->UpdateTenant($Data['Id'], $Data['PublicId'], $Data['Description']);
			case 'User':
				return \Aurora\Modules\Core\Module::Decorator()->UpdateUser($Data['Id'], $Data['PublicId'], 0, $Data['Role'], $Data['WriteSeparateLog']);
		}
		return false;
	}
	
	public function DeleteEntities($Type, $IdList)
	{
		$bResult = true;
		foreach ($IdList as $sId)
		{
			$bResult = $bResult && $this->DeleteEntity($Type, $sId);
		}
		return $bResult;
	}
	
	/**
	 * @api {post} ?/Api/ DeleteEntity
	 * @apiName DeleteEntity
	 * @apiGroup Core
	 * @apiDescription Deletes entity.
	 * 
	 * @apiHeader {string} Authorization "Bearer " + Authentication token which was received as the result of Core.Login method.
	 * @apiHeaderExample {json} Header-Example:
	 *	{
	 *		"Authorization": "Bearer 32b2ecd4a4016fedc4abee880425b6b8"
	 *	}
	 * 
	 * @apiParam {string=Core} Module Module name.
	 * @apiParam {string=DeleteEntity} Method Method name.
	 * @apiParam {string} Parameters JSON.stringified object <br>
	 * {<br>
	 * &emsp; **Type** *string* Entity type.<br>
	 * &emsp; **Id** *int* Entity identifier.<br>
	 * }
	 * 
	 * @apiParamExample {json} Request-Example:
	 * {
	 *	Module: 'Core',
	 *	Method: 'DeleteEntity',
	 *	Parameters: '{ Type: "Tenant", Id: 123 }'
	 * }
	 * 
	 * @apiSuccess {object[]} Result Array of response objects.
	 * @apiSuccess {string} Result.Module Module name.
	 * @apiSuccess {string} Result.Method Method name.
	 * @apiSuccess {bool} Result.Result Indicates if entity was deleted successfully.
	 * @apiSuccess {int} [Result.ErrorCode] Error code.
	 * 
	 * @apiSuccessExample {json} Success response example:
	 * {
	 *	Module: 'Core',
	 *	Method: 'DeleteEntity',
	 *	Result: true
	 * }
	 * 
	 * @apiSuccessExample {json} Error response example:
	 * {
	 *	Module: 'Core',
	 *	Method: 'DeleteEntity',
	 *	Result: false,
	 *	ErrorCode: 102
	 * }
	 */
	/**
	 * Deletes entity.
	 * 
	 * @param string $Type Entity type
	 * @param int $Id Entity identifier.
	 * @return bool
	 */
	public function DeleteEntity($Type, $Id)
	{
		switch ($Type)
		{
			case 'Tenant':
				return \Aurora\Modules\Core\Module::Decorator()->DeleteTenant($Id);
			case 'User':
				return \Aurora\Modules\Core\Module::Decorator()->DeleteUser($Id);
		}
		return false;
	}
	
	public function UpdateSettings(
			$DbLogin = null, $DbPassword = null, $DbName = null, $DbHost = null,
			$AdminLogin = null, $Password = null, $NewPassword = null, $AdminLanguage = null,
			$Language = null, $AutodetectLanguage = null, $TimeFormat = null, $EnableLogging = null,
			$EnableEventLogging = null, $LoggingLevel = null
	)
	{
		return \Aurora\Modules\Core\Module::Decorator()->UpdateSettings(
			$DbLogin, $DbPassword, $DbName, $DbHost,
			$AdminLogin, $Password, $NewPassword, $AdminLanguage,
			$Language, $AutodetectLanguage, $TimeFormat, $EnableLogging,
			$EnableEventLogging, $LoggingLevel
		);
	}
}
