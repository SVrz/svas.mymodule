<?
/**
 * Created by PhpStorm
 * User: Sergey Vasiliev
 */
include_once(dirname(__DIR__).'/lib/main.php');

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\EventManager;
use \Bitrix\Main\Config as Conf;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Application;
use \Svas\Mymodule\Main;

Loc::loadMessages(__FILE__);
Class svas_mymodule extends CModule
{
    var $exclusionAdminFiles;

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__."/version.php");
/**/
        $this->exclusionAdminFiles=array(
            '..',
            '.',
            'menu.php',
            'operation_description.php',
            'task_description.php'
        );
/**/
        $this->MODULE_ID = 'svas.mymodule';
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("SVAS_MYMODULE_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("SVAS_MYMODULE_MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage("SVAS_MYMODULE_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("SVAS_MYMODULE_PARTNER_URI");

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS='Y';
        $this->MODULE_GROUP_RIGHTS = "Y";
    }

    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot=false)
    {
        if($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(),'',dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    //Проверяем что система поддерживает D7
    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        if(!Application::getConnection(\Svas\Mymodule\BookTable::getConnectionName())->isTableExists(
            Base::getInstance('\Svas\Mymodule\BookTable')->getDBTableName()
        )
        )
        {
            Base::getInstance('\Svas\Mymodule\BookTable')->createDbTable();
        }
    }

    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        Application::getConnection(\Svas\Mymodule\BookTable::getConnectionName())->
        queryExecute('drop table if exists '.Base::getInstance('\Svas\Mymodule\BookTable')->getDBTableName());

        Option::delete($this->MODULE_ID);
    }

    function InstallEvents()
    {
        \Bitrix\Main\EventManager::getInstance()->registerEventHandler('main', 'OnBuildGlobalMenu', Main::MODULE_ID, '\Svas\Mymodule\globalMenu', 'AddGlobalMenuItem');
        \Bitrix\Main\EventManager::getInstance()->registerEventHandler($this->MODULE_ID, 'TestEventD7', $this->MODULE_ID, '\Svas\Mymodule\Event', 'eventHandler');
    }

    function UnInstallEvents()
    {
        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler('main', 'OnBuildGlobalMenu', Main::MODULE_ID, '\Svas\Mymodule\globalMenu', 'AddGlobalMenuItem');
        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler($this->MODULE_ID, 'TestEventD7', $this->MODULE_ID, '\Svas\Mymodule\Event', 'eventHandler');
    }

    function InstallFiles($arParams = array())
    {
        $path=$this->GetPath()."/install/components";

        if(\Bitrix\Main\IO\Directory::isDirectoryExists($path))
            CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($path);

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin'))
        {
            CopyDirFiles($this->GetPath() . "/install/admin/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"); //если есть файлы для копирования
            if ($dir = opendir($path))
            {
                while (false !== $item = readdir($dir))
                {
                    if (in_array($item,$this->exclusionAdminFiles))
                        continue;
                    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$this->MODULE_ID.'_'.$item,
                        '<'.'? require($_SERVER["DOCUMENT_ROOT"]."'.$this->GetPath(true).'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }

        return true;
    }

    function UnInstallFiles()
    {
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/svas/');

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin')) {
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . '/install/admin/', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin');
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles))
                        continue;
                    \Bitrix\Main\IO\File::deleteFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $this->MODULE_ID . '_' . $item);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        if($this->isVersionD7())
        {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();

            #работа с .settings.php
            $configuration = Conf\Configuration::getInstance();
            $svas_module_d7=$configuration->get('svas_module_d7');
            $svas_module_d7['install']=$svas_module_d7['install']+1;
            $configuration->add('svas_module_d7', $svas_module_d7);
            $configuration->saveConfiguration();
            #работа с .settings.php
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("SVAS_MYMODULE_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("SVAS_MYMODULE_INSTALL_TITLE"), $this->GetPath()."/install/step.php");
    }

    function DoUninstall()
    {
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if($request["step"]<2)
        {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("SVAS_MYMODULE_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep1.php");
        }
		elseif($request["step"]==2)
        {
            $this->UnInstallFiles();
            $this->UnInstallEvents();

            if($request["savedata"] != "Y")
                $this->UnInstallDB();

            \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

            #работа с .settings.php
            $configuration = Conf\Configuration::getInstance();
            $svas_module_d7=$configuration->get('svas_module_d7');
            $svas_module_d7['uninstall']=$svas_module_d7['uninstall']+1;
            $configuration->add('svas_module_d7', $svas_module_d7);
            $configuration->saveConfiguration();
            #работа с .settings.php

            $APPLICATION->IncludeAdminFile(Loc::getMessage("SVAS_MYMODULE_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep2.php");
        }
    }

    function GetModuleRightList()
    {
        return array(
            "reference_id" => array("D","K","S","W"),
            "reference" => array(
                "[D] ".Loc::getMessage("SVAS_MYMODULE_DENIED"),
                "[K] ".Loc::getMessage("SVAS_MYMODULE_READ_COMPONENT"),
                "[S] ".Loc::getMessage("SVAS_MYMODULE_WRITE_SETTINGS"),
                "[W] ".Loc::getMessage("SVAS_MYMODULE_FULL"))
        );
    }
}
?>