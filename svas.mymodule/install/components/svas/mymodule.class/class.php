<?php
/**
 * Created by PhpStorm
 * User: Sergey Pokoev
 * www.pokoev.ru
 * @ Академия 1С-Битрикс - 2015
 * @ academy.1c-bitrix.ru
 */
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

class MymoduleClass extends CBitrixComponent
{
    var $test;

    protected function checkModules()
    {
        if (!Loader::includeModule('svas.mymodule'))
        {
            ShowError(Loc::getMessage('SVAS_MYMODULE_MODULE_NOT_INSTALLED'));
            return false;
        }

        return true;
    }


    function var1()
    {
        $result = 'Это тестирование работы компонента.';

        return $result;
    }



    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        if($this -> checkModules())
        {
            /*Ваш код*/
            $result = $this->var1();

            $this->arResult=$result;

            $this->includeComponentTemplate();
        }
    }
};