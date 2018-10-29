<?
/**
 * Created by PhpStorm.
 * User: Sergey Pokoev
 * www.pokoev.ru
 */
include_once(dirname(__DIR__).'/lib/main.php');

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\EventManager;
use \Bitrix\Main\ModuleManager;
use \SVas\MyModule\Main;
Loc::loadMessages(__FILE__);
Class svas_mymodule extends CModule
{
	var $MODULE_ID = 'svas.mymodule';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(__DIR__."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("SVAS_MYMODULE_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("SVAS_MYMODULE_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("SVAS_MYMODULE_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("SVAS_MYMODULE_PARTNER_URI");
	}

	function InstallEvents()
	{
        EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', Main::MODULE_ID, '\Svas\Mymodule\globalMenu', 'AddGlobalMenuItem');

		return true;
	}

	function UnInstallEvents()
	{
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBuildGlobalMenu', Main::MODULE_ID, '\Svas\Mymodule\globalMenu', 'AddGlobalMenuItem');

		return true;
	}

	function InstallFiles($arParams = array())
	{
        CopyDirFiles(Main::GetPatch()."/install/components/svas", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/svas", true, true);

        return true;
	}

	function UnInstallFiles()
	{
        DeleteDirFilesEx("/bitrix/components/svas/class");

		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
        if(Main::isVersionD7())
        {
            $this->InstallTasks();
            $this->InstallFiles();
            $this->InstallEvents();
            ModuleManager::registerModule(Main::MODULE_ID);
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("SVAS_MYMODULE_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("SVAS_MYMODULE_INSTALL_TITLE"), Main::GetPatch()."/install/step.php");
	}

	function DoUninstall()
	{
        ModuleManager::unRegisterModule(Main::MODULE_ID);
		$this->UnInstallEvents();
		$this->UnInstallFiles();
        $this->UnInstallTasks();
	}
}
?>
