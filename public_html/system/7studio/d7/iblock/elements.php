<?
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Loader,
    Bitrix\Iblock\ElementTable;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();

$arParams = [
    "MODULE" => "iblock",
    "ELEMENTS" => [
        "FILTER" => [
            "IBLOCK_ID" => 2
        ],
        "SELECT" => [
            "ID",
            "NAME"
        ]
    ]
];

$arResult = array();

Loader::includeModule($arParams["MODULE"]);

$elements = ElementTable::getList(array(
    "filter" => $arParams["ELEMENTS"]["FILTER"],
    "select" => $arParams["ELEMENTS"]["SELECT"]
));
while ($element = $elements->fetch()){
    $arResult[] = $element;
}

d($arResult);