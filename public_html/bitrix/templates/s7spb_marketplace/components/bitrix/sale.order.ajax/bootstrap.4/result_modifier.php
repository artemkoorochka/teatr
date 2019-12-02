<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

foreach ($arResult["JS_DATA"]["GRID"]["ROWS"] as $key=>$arItem)
{

    $l = $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_L_ctn_VALUE"][0]["value"];
    $h = $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_H_ctn_VALUE"][0]["value"];
    $w = $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_W_ctn_VALUE"][0]["value"];

    if($l <= 0)
        $l = 1;
    if($h <= 0)
        $h = 1;
    if($w <= 0)
        $w = 1;

    $l = $l * $h * $w;

    $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_L_ctn_VALUE"][0]["value"] = $l . "m<sup>3</sup>";
    unset($arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_W_ctn_VALUE"][0]);
    unset($arResult["JS_DATA"]["GRID"]["ROWS"][$key]["columns"]["PROPERTY_H_ctn_VALUE"][0]);

}

