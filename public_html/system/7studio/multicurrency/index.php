<?
/**
 * @var CMain $APPLICATION
 * Добавляем формирование заказа по 3 типам цен:
 * Цена за штуку, стандарт
 * Цена за штуку, срочная доставка
 * Цена за штуку
 * На странице корзина добавляем переключатель, по изменению которого пересчитывается корзина и меняются цены.
 */
$arParams = array();
$arResult = array();


require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("/system/7studio/multicurrency/index.php");
$APPLICATION->RestartBuffer();

CModule::IncludeModule("sale");

$dbBasketItems = CSaleBasket::GetList(false,
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL",
        "DELAY" => "N",
        "CAN_BUY" => "Y"
    ),
    false,
    false,
    array("ID", "MODULE", "PRODUCT_ID", "CALLBACK_FUNC", "QUANTITY", "DELAY", "CAN_BUY", "PRICE")
);
while ($arItem = $dbBasketItems->Fetch())
{
    $arResult[] = $arItem;
}

d($arResult);
?>