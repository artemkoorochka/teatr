<?
/**
 * https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=5753&LESSON_PATH=3913.5062.5748.5063.5753
 * https://dev.1c-bitrix.ru/support/forum/forum6/topic114267/
 * @var CMain $APPLICATION
 *
 * TODO Нужно видить SQL
 */

use Bitrix\Main\Loader,
    Bitrix\Iblock\ElementTable;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();

$iblockId = \Studio7spb\Marketplace\CMarketplaceOptions::getInstance()->getOption('catalog_iblock_id');
$arParams = [
    "MODULE" => "iblock",
    "ELEMENTS" => [
        "FILTER" => [
            "IBLOCK_ID" => $iblockId
        ],
        "SELECT" => [
            "ID",
            "NAME",
            "IBLOCK_SECTION_ID",
            "SECTION.ID",
            "SECTION.NAME"
        ]
    ]
];

$arResult = array();

Loader::includeModule($arParams["MODULE"]);

$elements = ElementTable::getList(array(
    'order' => ["ID" => "asc"],
    "filter" => $arParams["ELEMENTS"]["FILTER"],
    "select" => $arParams["ELEMENTS"]["SELECT"],
    "runtime" => [
        "SECTION" => [
            "data_type" => "\Bitrix\Iblock\SectionTable",
            'reference' => [
                '=this.IBLOCK_SECTION_ID' => 'ref.ID'
            ],
            'join_type' => 'inner'
        ]
    ]
));


while ($element = $elements->fetch()){
    $arResult[] = $element;
}

d($arResult);