<?
/**
 * https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=5753&LESSON_PATH=3913.5062.5748.5063.5753
 * https://dev.1c-bitrix.ru/support/forum/forum6/topic114267/
 * @var CMain $APPLICATION
 * Отладка SQL-запросов
 * https://mrcappuccino.ru/blog/post/bitrix-d7-debug
 * https://dev.1c-bitrix.ru/support/forum/forum6/topic88441/
 * https://gist.github.com/chebanenko/666c612c34b321fdcc13
 * Using SQL Tracker
 * Cosole and comands line
 */

use Bitrix\Main\Loader,
    Bitrix\Iblock\ElementTable,
    Bitrix\Iblock\PropertyIndex,
    Bitrix\Main\Diag;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();

$iblockId = \Studio7spb\Marketplace\CMarketplaceOptions::getInstance()->getOption('catalog_iblock_id');
$arParams = [
    "MODULE" => "iblock",
    "ELEMENTS" => [
        "LIMIT" => 10,
        "FILTER" => [
            "IBLOCK_ID" => $iblockId
        ],
        "SELECT" => [
            "ID",
            "NAME",
            "IBLOCK_SECTION_ID",
            "SECTION.ID",
            "SECTION.NAME",
            "PROPERTY.ID",
            "PROPERTY.NAME",
            "LINK.ID",
            //"LINK.VALUE"
        ]
    ]
];

$arResult = array();

Loader::includeModule($arParams["MODULE"]);

/**
 * Use Tracker
 */
$connection = Bitrix\Main\Application::getConnection();
$tracker = $connection->startTracker(true); // Для того, чтобы очистить данные в трекере и начать новое отслеживание, нужно вызвать startTracker с аргументом $reset = true:
$elements = ElementTable::getList(array(
    'order' => ["ID" => "asc"],
    "filter" => $arParams["ELEMENTS"]["FILTER"],
    "select" => $arParams["ELEMENTS"]["SELECT"],
    "limit" => $arParams["ELEMENTS"]["LIMIT"],
    "runtime" => [
        "SECTION" => [
            "data_type" => "\Bitrix\Iblock\SectionTable",
            'reference' => [
                '=this.IBLOCK_SECTION_ID' => 'ref.ID'
            ],
            'join_type' => 'inner'
        ],
        'PROPERTY' => [
            'data_type' => 'Bitrix\Iblock\PropertyTable',
            'reference' => ['=this.IBLOCK_ID' => 'ref.IBLOCK_ID'],
            'join_type' => "LEFT",
        ],
        'LINK' => [
            'data_type' => 'float',
            'expression' => [
                '(SELECT ID, VALUE
                FROM b_iblock_element_property
                WHERE b_iblock_element_property.IBLOCK_PROPERTY_ID=%s
                    AND b_iblock_element_property.IBLOCK_ELEMENT_ID=%s)',
                'PROPERTY.ID',
                'ID',
            ],
        ],
    ]
));
$connection->stopTracker();

foreach ($tracker->getQueries() as $query) {
    d($query->getSql()); // Текст запроса
    //d($query->getTrace()); // Стек вызовов функций, которые привели к выполнению запроса
    d($query->getTime()); // Время выполнения запроса в секундах
}

while ($element = $elements->fetch()){
    $arResult[] = $element;
}

d($arResult);

