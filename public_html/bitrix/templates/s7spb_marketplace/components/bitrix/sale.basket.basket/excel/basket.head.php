<?php
/**
 * @var PHPExcel $objPHPExcel
 * @var array $arParams
 * @var array $arItem
 */
$i = 1;
use Bitrix\Main\Localization\Loc;

$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_NAME"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;

if($arResult["CURRENCY"] == "USD"){
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_PRICE", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]))), PHPExcel_Cell_DataType::TYPE_STRING);
}else{
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_PRICE_NDS", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]))), PHPExcel_Cell_DataType::TYPE_STRING);
}
$i++;

foreach ($arParams["COLUMNS_HEADER"] as $value){
    if(strpos($value, 'PROPERTY') !== false){
        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEM_" . $value), PHPExcel_Cell_DataType::TYPE_STRING);
    }
    $i++;
}

if($arResult["CURRENCY"] == "USD"){
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_SUM", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]))), PHPExcel_Cell_DataType::TYPE_STRING);
}else{
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_SUM_NDS", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]))), PHPExcel_Cell_DataType::TYPE_STRING);
}


// send matrix position to new row for busket items
$arParams["MATRIX"]["CELL"]["Y"]++;
$arParams["MATRIX"]["CELL"]["X"] = $arParams["MATRIX"]["START"]["X"];
$arParams["MATRIX"]["CELL"]["ADRESS"] = $arParams["MATRIX"]["CELL"]["X"] . $arParams["MATRIX"]["CELL"]["Y"] ;
?>