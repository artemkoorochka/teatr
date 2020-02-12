<?
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Sale\Basket,
    Bitrix\Main\Loader,
    Studio7spb\Marketplace\MultipleBasketTable;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->RestartBuffer();

Loader::includeModule("sale");


/**
 * Get basket items
 */
$arParams = array(
    "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
    "LID" => SITE_ID,
    "ORDER_ID" => "NULL"
);
$arResult = array(
    "ITEMS" => array(),
    "PRODUCTS" => array(),
    "PARAMS" => array(),
    //
    "BASKETS" => array(),
);

$basketItems = Basket::getList(array(
    "filter" => $arParams,
    "select" => array(
        "ID",
        "NAME",
        "FUSER_ID",
        "PRODUCT_ID",
        "PRICE",
        "BASE_PRICE",
        "PRODUCT_PRICE_ID",
        "CURRENCY",
        "QUANTITY",
    )
));

while ($basketItem = $basketItems->fetch())
{
    $arResult["ITEMS"][] = $basketItem;
    $arResult["PRODUCTS"][] = $basketItem["PRODUCT_ID"];
    $arResult["PARAMS"][$basketItem["PRODUCT_ID"]] = $basketItem;
}

$arResult["PRODUCTS"] = serialize($arResult["PRODUCTS"]);
$arResult["PARAMS"] = serialize($arResult["PARAMS"]);
$arResult["PRODUCTS"] = array(
    "LID" => SITE_ID,
    "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
    "PRODUCTS" => $arResult["PRODUCTS"],
    "PARAMS" => $arResult["PARAMS"]
);


MultipleBasketTable::add($arResult["PRODUCTS"]);

unset($arResult["PARAMS"]);
unset($arResult["PRODUCTS"]);

/**
 * Get Baskets
 */

$arResult["BASKETS"] = MultipleBasketTable::getList();
$arResult["BASKETS"] = $arResult["BASKETS"]->getSelectedRowsCount();


/**
 * Output
 */
d($arResult);