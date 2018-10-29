<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Created by PhpStorm
 * User: Sergey Pokoev
 * www.pokoev.ru
 * @ Академия 1С-Битрикс - 2015
 * @ academy.1c-bitrix.ru
 */
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
/*
echo'<pre>';
var_dump($arResult);
echo'</pre>';
*/
?>
<table class="adm-list-table" id="t_perfmon_all_tables">
    <thead>
    <tr class="adm-list-table-header">
        <td class="adm-list-table-cell adm-list-table-cell-sort" title="ID">
            <div class="adm-list-table-cell-inner">ID</div>
        </td>
        <td class="adm-list-table-cell adm-list-table-cell-sort" title="NAME">
            <div class="adm-list-table-cell-inner">Название книги</div>
        </td>
        <td class="adm-list-table-cell adm-list-table-cell-sort" title="ISBN">
            <div class="adm-list-table-cell-inner">ISBN</div>
        </td>
        <td class="adm-list-table-cell adm-list-table-cell-sort" title="Автор">
            <div class="adm-list-table-cell-inner">Автор</div>
        </td>
        <td class="adm-list-table-cell adm-list-table-cell-sort" title="Год издания">
            <div class="adm-list-table-cell-inner">Год издания</div>
        </td>
        <td class="adm-list-table-cell adm-list-table-cell-sort" title="Краткое описание">
            <div class="adm-list-table-cell-inner">Краткое описание</div>
        </td>
        <td class="adm-list-table-cell adm-list-table-cell-sort" title="Страниц">
            <div class="adm-list-table-cell-inner">Страниц</div>
        </td>
    </tr>
    </thead>
    <tbody>
    <?foreach($arResult as $arItem):?>
        <tr class="adm-list-table-row">
            <td class="adm-list-table-cell"><?=$arItem['ID']?></td>
            <td class="adm-list-table-cell"><?=$arItem['NAME']?></td>
            <td class="adm-list-table-cell"><?=$arItem['ISBN']?></td>
            <td class="adm-list-table-cell"><?=$arItem['AUTHOR']?></td>
            <td class="adm-list-table-cell align-right"><?=$arItem['AGE_YEAR']?></td>
            <td class="adm-list-table-cell"><?=$arItem['DESCRIPTION']?></td>
            <td class="adm-list-table-cell align-right adm-list-table-cell-last"><?=$arItem['WRITE_COUNT']?></td>
        </tr>
    <?endforeach;?>
    </tbody>
</table>
