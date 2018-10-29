<?php
/**
 * Created by PhpStorm
  * @ academy.1c-bitrix.ru
 */
use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type;
use \Svas\Mymodule\BookTable;

class mymoduleOrmGetlist extends CBitrixComponent
{

    /**
     * проверяет подключение необходиимых модулей
     * @throws LoaderException
     */
    protected function checkModules()
    {
        if (!Main\Loader::includeModule('svas.mymodule'))
            throw new Main\LoaderException(Loc::getMessage('SVAS_MYMODULE_MODULE_NOT_INSTALLED'));
    }

    function var1()
    {

        $result = BookTable::getList(array(
            'select'  => array('ID','NAME_BOOK' =>'NAME','AGE_YEAR','WRITE_COUNT'), // имена полей, которые необходимо получить в результате
            'filter'  => array('WRITE_COUNT' => 0), // описание фильтра для WHERE и HAVING
            //'group'   => array(), // явное указание полей, по которым нужно группировать результат
            'order'   => array('ID'=>'DESC'), // параметры сортировки
//            'limit'   => 3, // количество записей
//            'offset'  => 2, // смещение для limit
        ));


/*
        $result = BookTable::getList(array(
        ));
*/

        return $result;
    }

    function var2()
    {
        $result = BookTable::getList(array(
        ));
        return $result;
    }


    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        $this -> checkModules();

        $result = $this->var2();

        //Вариант 1 получения данных
        while ($row = $result->fetch())
        {
            $this -> arResult[] = $row;
        }

        //Вариант 2 получения данных
//        $this -> arResult = $result->fetchAll();

        $this->includeComponentTemplate();
    }
};