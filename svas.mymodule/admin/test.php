<?php
/**
 * Created by PhpStorm
 * User: Sergey Vasiliev
 *
 * menu admin test
 */
define('ADMIN_MODULE_NAME', 'svas.mymodule');

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';
require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/svas.mymodule/include.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/svas.mymodule/prolog.php");
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';
IncludeModuleLangFile(__FILE__);
echo "Добро пожаловать в админ-панель <b>Демо-модуля</b>!";
?>
    <?/*$APPLICATION->IncludeComponent(
        "svas:mymodule.class",
        "",
        Array()
    );*/?><br>
    <p>
    <?$APPLICATION->IncludeComponent(
        "svas:mymodule.orm.getlist",
        "",
        Array()
    );?><br>
    </p>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';