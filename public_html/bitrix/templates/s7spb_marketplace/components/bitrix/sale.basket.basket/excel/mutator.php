<?
use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
Loc::loadLanguageFile(__FILE__);
Loader::includeModule("iblock");

/**
 *
 * This file modifies result for every request (including AJAX).
 * Use it to edit output result for "{{ mustache }}" templates.
 *
 * @var array $result
 * Prepare
 */

$result["MARKET_LIST"] = array();
$result["SPACE"] = array(
    "TOTAL" => array(
        "LHW_ctn" => 0,
        "WEIGHT" => 0
    ),
    "NUMBER_ROUND" => 2
);


/**
 * Basket items
 */
foreach ($this->basketItems as $arItem)
{
    if($arItem["CAN_BUY"] == "Y"){
        $element = CIBlockElement::GetList(array(), array("ID" => $arItem["PRODUCT_ID"]), false, false, array(
            "ID",
            "IBLOCK_ID",
            "PROPERTY_H_COMPANY.ID",
            "PROPERTY_H_COMPANY.NAME",
            "PROPERTY_H_COMPANY.PREVIEW_TEXT",
            "PROPERTY_WEIGHT",
        ));
        if($element = $element->GetNext()){

            if($element["PROPERTY_H_COMPANY_ID"] > 0){
                if(empty($result["MARKET_LIST"][$element["PROPERTY_H_COMPANY_ID"]])){
                    $result["MARKET_LIST"][$element["PROPERTY_H_COMPANY_ID"]] = array(
                        "NAME" => $element["PROPERTY_H_COMPANY_NAME"],
                        "ITEMS" => array()
                    );
                }

                $result["MARKET_LIST"][$element["PROPERTY_H_COMPANY_ID"]]["ITEMS"][] = $element["ID"];

            }

        }

        /**
         * total space
         */
        if($arItem["QUANTITY"] > 0 && $arItem["PROPERTY_Master_CTN_PCS_VALUE"] > 0){
            $result["SPACE"]["TOTAL"]["LHW_ctn"] += round($arItem["QUANTITY"] / $arItem["PROPERTY_Master_CTN_PCS_VALUE"] * $arItem["PROPERTY_Master_CTN_CBM_VALUE"], $result["SPACE"]["NUMBER_ROUND"]);
            $result["SPACE"]["TOTAL"]["WEIGHT"] += round($arItem["PROPERTY_WEIGHT_VALUE"] * $arItem["QUANTITY"] / $arItem["PROPERTY_Master_CTN_PCS_VALUE"], 0);
        }
    }
}

// CURRENCIES FORMAT
$result["CURRENCIES_FORMAT"] = array();
foreach ($result["CURRENCIES"] as $currency){
    $result["CURRENCIES_FORMAT"][$currency["CURRENCY"]] = $currency["FORMAT"]["FORMAT_STRING"];
}