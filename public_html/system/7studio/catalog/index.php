<?
use Bitrix\Main\Loader,
    Bitrix\Iblock\ElementTable;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

Loader::includeModule("iblock");
$arResult = array();
$neo = new CIBlockElement();

$elements = ElementTable::getList(array(
    "filter" => array(
        "IBLOCK_ID" => 2
    )
));
