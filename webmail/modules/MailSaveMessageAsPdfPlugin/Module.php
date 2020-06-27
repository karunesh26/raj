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

namespace Aurora\Modules\MailSaveMessageAsPdfPlugin;

/**
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractModule
{
	/**
	 * @param int $UserId
	 * @param string $FileName
	 * @param string $Html
	 * @return boolean
	 */
	public function GeneratePdfFile($UserId, $FileName, $Html)
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::NormalUser);
		
		$sFileName = $FileName . '.pdf';

		$sUUID = \Aurora\System\Api::getUserUUIDById($UserId);
		$sTempName = md5($sUUID.$sFileName.microtime(true));

		$sExec = \Aurora\System\Api::DataPath().'/system/wkhtmltopdf/linux/wkhtmltopdf';
		if (!\file_exists($sExec))
		{
			$sExec = \Aurora\System\Api::DataPath().'/system/wkhtmltopdf/win/wkhtmltopdf.exe';
			if (!\file_exists($sExec))
			{
				$sExec = '';
			}
		}
		
		if (0 < \strlen($sExec))
		{
			$oSnappy = new \Knp\Snappy\Pdf($sExec);
			$oSnappy->setOption('quiet', true);
			$oSnappy->setOption('disable-javascript', true);
			$oSnappy->setOption('encoding', 'utf-8');

			$oApiFileCache = new \Aurora\System\Managers\Filecache();
			
			$oSnappy->generateFromHtml($Html,
				$oApiFileCache->generateFullFilePath($sUUID, $sTempName, '', $this->GetName()), array(), true);
			
			return \Aurora\System\Utils::GetClientFileResponse(
				$this->GetName(), $UserId, $sFileName, $sTempName, $oApiFileCache->fileSize($sUUID, $sTempName, '', $this->GetName())
			);
		}
		else
		{
			throw new \Aurora\System\Exceptions\ApiException(\Aurora\System\Notifications::LibraryNoFound);
		}

		return false;
	}
}
