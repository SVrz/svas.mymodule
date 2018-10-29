<?php
/**
 * Created by PhpStorm.
 * User: Sergey Vasiliev
 */

namespace Svas\Mymodule;

class globalMenu
{
    function AddGlobalMenuItem(&$aGlobalMenu, &$aModuleMenu)
    {
        $aModuleMenu[] = array(
            "parent_menu" => "global_menu_custom",
            "icon" => "default_menu_icon",
            "page_icon" => "default_page_icon",
            "sort"=>"100",
            "text"=>"страница настроек",
            "title"=>"Custom Tille",
            "url"=>"/bitrix/admin/svas.mymodule_test.php",
            "more_url"=>array(),
        );

        $arRes = array(
            "global_menu_custom" => array(
                "menu_id" => "custom",
                "page_icon" => "services_title_icon",
                "index_icon" => "services_page_icon",
                "text" => "Демо-модуль",
                "title" => "Кнопка модуля",
                "sort" => 400,
                "items_id" => "global_menu_custom",
                "help_section" => "custom",
                "items" => array()
            ),
        );

        return $arRes;
    }
}

class tempModule
{
    function test()
    {

    }
}