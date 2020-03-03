<?
/**
 * Import settings page
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Studio7spb\Marketplace\CMarketplaceOptions,
    Studio7spb\Marketplace\ImportSettingsTable,
    Bitrix\Main\Loader,
    Bitrix\Sale\Basket,
    Bitrix\Iblock\ElementTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loc::loadLanguageFile(__FILE__);

$APPLICATION->SetTitle( Loc::getMessage("TITLE") );
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$request = Application::getInstance()->getContext()->getRequest();

// <editor-fold defaultstate="collapsed" desc="# Prepare properties">

$arParams = array(
    "MODULES" => ["sale", "iblock"],
    "CATALOG_IBLOCK_ID" => CMarketplaceOptions::getInstance()->getOption("catalog_iblock_id"),
    "PAGE" => $APPLICATION->GetCurPage()
);

if(!empty($arParams["MODULES"])){
    foreach ($arParams["MODULES"] as $module){
        Loader::includeModule($module);
    }
}
// </editor-fold>



$arResult = [
    "CONSTANTS" => [],
    "ORDER_BASKET_ITEMS" => [], // Ордернутые товары
    "CATALOG_ITEMS" => []
];


// <editor-fold defaultstate="collapsed" desc="# Get settings">
$constants = ImportSettingsTable::getList();
while ($constant = $constants->fetch())
{
    $arResult["CONSTANTS"][$constant["CODE"]] = $constant["VALUE"];
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Get Basket items">
$items = Basket::getList(array(
    "select" => [
        "ID",
        "PRODUCT_ID",
        "PRICE",
        "BASE_PRICE",
        "CURRENCY"
    ],
    "filter" => [
        "!ORDER_ID" => false
    ]
));
while ($item = $items->fetch()){
    $arResult["ORDER_BASKET_ITEMS"][] = $item;
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="Get Catalog items">
$items = ElementTable::getList([
    "filter" => [
        "IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"]
    ],
    "select" => [
        "ID",
        "IBLOCK_ID",
        "NAME"
    ]
]);
d($items->getSelectedRowsCount());
while ($item = $items->fetch()){
    $arResult["CATALOG_ITEMS"][] = $item;
}
// </editor-fold>
?>


<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>