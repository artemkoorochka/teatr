<?
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Sale\Order,
    Bitrix\Main\Loader;
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->RestartBuffer();
Loader::includeModule("sale");
$arParams = array(
    "ORDER_ID" => 60
);
$arResult = array(
    "ORDER" => array(),
    "BASKET" => array()
);

$orders = Order::getList(array(
    "filter" => array("ID" => $arParams["ORDER_ID"]),
    "select" => array(
        "ID",
        "CURRENCY",
        "PRICE"
    )
));
if($order = $orders->fetch())
{
    $arResult["ORDER"] = $order;
}

if(!empty($arResult["ORDER"])){
    $items = \Bitrix\Sale\Basket::getList(array(
        "filter" => array("ORDER_ID" => $arResult["ORDER"]["ID"]),
        "select" => array(
            "ID",
            "NAME",
            "PRICE",
            "BASE_PRICE",
            "PRODUCT_ID",
            "PRODUCT_PRICE_ID",
            "CURRENCY",
            "QUANTITY"
        )
    ));
    while ($item = $items->fetch()){
        $arResult["BASKET"][] = $item;
    }
}

d($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]);


d($arResult);