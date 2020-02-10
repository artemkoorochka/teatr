<?
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Sale\Order,
    Bitrix\Sale\Payment,
    Bitrix\Main\Loader;
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->RestartBuffer();
Loader::includeModule("sale");
$arParams = array(
    "ORDER_ID" => 63
);
$arResult = array(
    "ORDER" => array(),
    "BASKET" => array(),
    "PAYMENT" => array()
);

/**
 * Get order
 */
$orders = Order::getList(array(
    "filter" => array("ID" => $arParams["ORDER_ID"]),
    "select" => array(
        "ID",
        "CURRENCY",
        "PAY_SYSTEM_ID",
        "PRICE"
    )
));
if($order = $orders->fetch())
{
    $arResult["ORDER"] = $order;
}

/**
 * Get order  basket
 */
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

/**
 * Get order payment
 * https://dev.1c-bitrix.ru/api_d7/bitrix/sale/classes/payment/index.php
 */
$payment = Payment::getList(array(
    "filter" => array(
        "ORDER_ID" => $arResult["ORDER"]["ID"]
    )
));
if($payment = $payment->fetch())
{
    $arResult["PAYMENT"] = $payment;
}

d($arResult);