<?
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Sale\Basket,
    Bitrix\Main\Loader,
    Bitrix\Main\Web\Json,
    Studio7spb\Marketplace\MultipleBasketTable;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

Loader::includeModule("sale");

// clear
$arParams = array(
    "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
    "LID" => SITE_ID,
    "ORDER_ID" => "NULL"
);
$basketItems = Basket::getList(array(
    "filter" => $arParams,
    "select" => array("ID")
));
$neo = new CSaleBasket();
while ($basketItem = $basketItems->fetch())
{
    $neo->Delete($basketItem["ID"]);
}

// add
$basket = $_REQUEST["basket"];
$basket = MultipleBasketTable::getList(array(
    "filter" => array(
        "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ID" => $basket
    ),
    "select" => array(
        "ID",
        "PARAMS"
    )
));
if($basket = $basket->fetch())
{
    $basket["PARAMS"] = unserialize($basket["PARAMS"]);
    if($basket["PARAMS"]){
        foreach ($basket["PARAMS"] as $item) {

            unset($item["DATE_INSERT"]);
            unset($item["DATE_UPDATE"]);
            unset($item["XML_ID"]);

            CSaleBasket::Add($item);
        }
    }



}

LocalRedirect("/personal/baskets/");