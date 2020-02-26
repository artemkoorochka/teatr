<?
/**
 * https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=5753&LESSON_PATH=3913.5062.5748.5063.5753
 * https://dev.1c-bitrix.ru/support/forum/forum6/topic114267/
 * @var CMain $APPLICATION
 * Отладка SQL-запросов
 * https://mrcappuccino.ru/blog/post/bitrix-d7-debug
 * Using SQL Tracker
 * Cosole and comands line
 */

use Bitrix\Main\Loader,
    Bitrix\Iblock\ElementTable,
    Bitrix\Main\Diag;

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

/**
 * Use Tracker
 */
$connection = Bitrix\Main\Application::getConnection();
$tracker = $connection->startTracker(true); // Для того, чтобы очистить данные в трекере и начать новое отслеживание, нужно вызвать startTracker с аргументом $reset = true:
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
$connection->stopTracker();

foreach ($tracker->getQueries() as $query) {
    Diag\Debug::dump($query->getSql(), "SQL"); // Текст запроса
    //d($query->getTrace()); // Стек вызовов функций, которые привели к выполнению запроса
    d($query->getTime()); // Время выполнения запроса в секундах
}

while ($element = $elements->fetch()){
    $arResult[] = $element;
}

d($arResult);