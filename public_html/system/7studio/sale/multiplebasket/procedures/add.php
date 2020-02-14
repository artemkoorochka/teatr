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
    "ALERT" => null
);

$basketItems = Basket::getList(array(
    "filter" => $arParams,
    /*
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
    */
));

while ($basketItem = $basketItems->fetch())
{
    $arResult["ITEMS"][] = $basketItem;
    $arResult["PRODUCTS"][] = $basketItem["PRODUCT_ID"];
    unset($basketItem["ID"]);
    $arResult["PARAMS"][$basketItem["PRODUCT_ID"]] = $basketItem;
}

if(empty($arResult["PRODUCTS"])) {
    $arResult["ALERT"] = array("type" => "danger", "text" => "Текущая корзина пуста, необходимо вначале в разделе <a href='/catalog.'>каталог</a> наполнить корзину товарами");
}
else{

    $arResult["PRODUCTS"] = serialize($arResult["PRODUCTS"]);
    $arResult["PARAMS"] = serialize($arResult["PARAMS"]);
    $arResult["PRODUCTS"] = array(
        "LID" => SITE_ID,
        "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
        "PRODUCTS" => $arResult["PRODUCTS"],
        "PARAMS" => $arResult["PARAMS"]
    );

    # add to multiple basket

    $result =  MultipleBasketTable::add($arResult["PRODUCTS"]);

    if($result->isSuccess())
    {
        unset($arResult["PARAMS"]);
        unset($arResult["PRODUCTS"]);
        $neo = new CSaleBasket();
        foreach ($arResult["ITEMS"] as $basketItem){

            $neo->Delete($basketItem["ID"]);
        }
        $arResult["ALERT"] = array("type" => "success", "text" => "текущая корзина добавлена в список Моих корзин");
    }
}
# clear fuser basket

/**
 * Get Baskets
 */

$baskets = MultipleBasketTable::getList();
while ($basket = $baskets->fetch())
{
    $arResult["BASKETS"][] = $basket;
}

/**
 * Output
 */
//d($arResult["BASKETS"]);
//$arResult["ALERT"] = Json::encode($arResult["ALERT"]);

LocalRedirect("/personal/baskets/?type=" . $arResult["ALERT"]["type"] . "&text=" . $arResult["ALERT"]["text"]);