<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

$arResult["JS_DATA"]["TOTAL"]["SPACE_SUM"] = 0;
if(!empty($arResult["JS_DATA"]["GRID"]["ROWS"])){
    foreach ($arResult["JS_DATA"]["GRID"]["ROWS"] as $key=>$arItem)
    {
        $l = $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_Master_CTN_CBM_VALUE"][0]["value"];
        $l = $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_Master_CTN_PCS_VALUE"][0]["value"] / $l;
        if($l <= 0)
            $l = 1;


        $l = $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["data"]["QUANTITY"] / $l;
        $l = round($l, 2);
        $arResult["JS_DATA"]["TOTAL"]["SPACE_SUM"] = $arResult["JS_DATA"]["TOTAL"]["SPACE_SUM"] + $l;
        $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_Master_CTN_CBM_VALUE"][0]["value"] = $l . GetMessage("SPASE_METER") . "<sup>3</sup>";

        //unset($arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_Master_CTN_PCS"]);

        //$arResult["JS_DATA"]["TOTAL"]["ORDER_WEIGHT"]

        $arItem["CURRENCY"] = explode(" ", $arItem["data"]["PRICE_FORMATED"]);
        $arItem["CURRENCY"] = array_pop($arItem["CURRENCY"]);

        $arItem["PRICE_FORMATED"] = number_format($arItem["data"]["PRICE"], 0, '.', ' '); // . " " . $arItem["CURRENCY"];
        $arItem["SUM"] = number_format($arItem["data"]["SUM_NUM"], 0, '.', ' '); // . " " . $arItem["CURRENCY"];
        $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["data"]["PRICE_FORMATED"] = $arItem["PRICE_FORMATED"];
        $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["data"]["SUM"] = $arItem["SUM"];
    }
}

/**
 * Formating values
 */
$arResult["JS_DATA"]["TOTAL"]["SPACE_SUM"] = $arResult["JS_DATA"]["TOTAL"]["SPACE_SUM"] . GetMessage("SPASE_METER") . "<sup>3</sup>";
$arResult["JS_DATA"]["TOTAL"]["ORDER_WEIGHT_FORMATED"] = explode(" ", $arResult["JS_DATA"]["TOTAL"]["ORDER_WEIGHT_FORMATED"]);
$arResult["JS_DATA"]["TOTAL"]["ORDER_WEIGHT_FORMATED"][0] = round($arResult["JS_DATA"]["TOTAL"]["ORDER_WEIGHT_FORMATED"][0], 1);
$arResult["JS_DATA"]["TOTAL"]["ORDER_WEIGHT_FORMATED"] = implode(" ", $arResult["JS_DATA"]["TOTAL"]["ORDER_WEIGHT_FORMATED"]);


//$arResult["JS_DATA"]["ORDER_PROP"]["properties"][3]["VALUE"] = array(90713);
/**
 * Formating HEADERS
 */
if(!empty($arResult["JS_DATA"]["GRID"]["HEADERS"])){
    foreach ($arResult["JS_DATA"]["GRID"]["HEADERS"] as $key=>$arItem){
        if($arItem["id"] == "SUM" || $arItem["id"] == "PRICE_FORMATED"){
            if($arResult["ORDER_DATA"]["CURRENCY"] == "USD"){
                $arResult["JS_DATA"]["GRID"]["HEADERS"][$key]["name"] .= ", " . GetMessage("SOA_CURRENCY_USD");
            }else{
                $arResult["JS_DATA"]["GRID"]["HEADERS"][$key]["name"] .= ", " . GetMessage("SOA_CURRENCY");
            }
        }
    }
}