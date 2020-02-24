<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Sale\Basket;

if ($arParams['SHOW_ORDER_PAGE'] !== 'Y')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}	

if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

include("left_menu.php");
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_ORDERS"), $arResult['PATH_TO_ORDERS']);

// <editor-fold defaultstate="collapsed" desc=" # Company">
$company = CIBlockElement::GetList(
    array(),
    array(
        "IBLOCK_ID" => 6,
        "PROPERTY_COMP_USER" => $USER->GetID()
    ),
    false,
    false,
    array(
        "ID",
        "PROPERTY_COMP_COMMISSION"
    )
);

if($company = $company->Fetch()){
    $company["PRODUCTS"] = array();
    $company["SUM"] = 0;
    $products = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => 2,
            "PROPERTY_H_COMPANY" => $company["ID"]
        ),
        false,
        false,
        array(
            "ID"
        )
    );
    while ($product = $products->Fetch())
    {
        $company["PRODUCTS"][] = $product["ID"];
    }

    $arResult["COMPANY"] = $company;
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc=" # Basket Items calculate order sum">
if($arResult["COMPANY"]["ID"] > 0 && !empty($arResult["COMPANY"]["PRODUCTS"])){
    Loader::includeModule("sale");
    $basketItems = Basket::getList(array(
        "filter" => array(
            "!ORDER_ID" => false,
            "PRODUCT_ID" => $arResult["COMPANY"]["PRODUCTS"]
        ),
        "select" => array("PRICE")
    ));
    while ($basketItem = $basketItems->fetch()){
        //$arResult["ITEMS"][] = $basketItem;
        $arResult["COMPANY"]["SUM"] += $basketItem["PRICE"];
    }
}

$arResult["COMPANY"]["SUM"] = CurrencyFormat($arResult["COMPANY"]["SUM"], "RUB");
// </editor-fold>
?>
<div class="s7sbp--marketplace--saler--lk--right--inner">

	<div class="s7sbp--marketplace--saler--lk--title ff--roboto">комиссионное вознаграждении за текущий месяц</div>
	<p>Информация о начисленном комиссионном вознаграждении за текущий месяц</p>
	<p><strong>Ставка комиссионного вознаграждения: </strong> <?=$arResult["COMPANY"]["PROPERTY_COMP_COMMISSION_VALUE"]?> %</p>
	<p><strong>Сумма продаж: </strong> <?=$arResult["COMPANY"]["SUM"]?></p>
	<p><strong>Сумма комиссионного вознаграждения: </strong> <?=$arResult["COMPANY"]["PROPERTY_COMP_COMMISSION_VALUE"]?> %</p>

</div>


