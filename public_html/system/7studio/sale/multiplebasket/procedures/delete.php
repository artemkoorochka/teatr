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
LocalRedirect("/personal/baskets/");