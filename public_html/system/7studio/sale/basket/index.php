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
    "BASKETS" => array(),
);

$basketItems = Basket::getList(array(
    "filter" => $arParams,
    "select" => array(
        "ID",
        "NAME",
        "PRODUCT_ID"
    )
));

while ($basketItem = $basketItems->fetch())
{
    $arResult["ITEMS"][] = $basketItem;
}

/**
 * Get Baskets
 */

$baskets = MultipleBasketTable::getList();

foreach ($baskets as $basket=>$er)
{
    $arResult["BASKETS"][] = $basket;
}

$arResult["BASKETS"] = implode('", <br>"', $arResult["BASKETS"]);
$arResult["BASKETS"] = '"' . $arResult["BASKETS"];
$arResult["BASKETS"] .= '"';

/**
 * Output
 */
d($arResult);


echo $arResult["BASKETS"];