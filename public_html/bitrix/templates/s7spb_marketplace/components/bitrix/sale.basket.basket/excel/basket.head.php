<?php
/**
 * @var PHPExcel $objPHPExcel
 * @var array $arParams
 * @var array $arItem
 */
$i = 1;
use Bitrix\Main\Localization\Loc;

# Stylesheet
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setIndent(2);
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getRowDimension($arParams["MATRIX"]["CELL"]["Y"])->setRowHeight(18);
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CECECE');

# Data filling
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_NAME"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;

$objPHPExcel->getActiveSheet()->getColumnDimension($arParams["ALFAVITE"][$i])->setWidth(24);
if($arResult["CURRENCY"] == "USD"){
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_PRICE", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]))), PHPExcel_Cell_DataType::TYPE_STRING);
}else{
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_PRICE_NDS", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]))), PHPExcel_Cell_DataType::TYPE_STRING);
}
$i++;


foreach ($arParams["COLUMNS_HEADER"] as $value){
    $objPHPExcel->getActiveSheet()->getColumnDimension($arParams["ALFAVITE"][$i])->setWidth(24);
    if($i===6){
        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_QUANTITY"), PHPExcel_Cell_DataType::TYPE_STRING);
        $i++;
    }
    if(strpos($value, 'PROPERTY') !== false){
        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEM_" . $value), PHPExcel_Cell_DataType::TYPE_STRING);
    }
    $i++;
}

$objPHPExcel->getActiveSheet()->getColumnDimension($arParams["ALFAVITE"][$i])->setWidth(24);
if($arResult["CURRENCY"] == "USD"){
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_SUM", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]))), PHPExcel_Cell_DataType::TYPE_STRING);
}else{
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_ITEMS_HEAD_SUM_NDS", array("CURRENCY" => str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]))), PHPExcel_Cell_DataType::TYPE_STRING);
}

# send matrix position to new row for busket items
$arParams["MATRIX"]["CELL"]["Y"]++;
$arParams["MATRIX"]["CELL"]["X"] = $arParams["MATRIX"]["START"]["X"];
$arParams["MATRIX"]["CELL"]["ADRESS"] = $arParams["MATRIX"]["CELL"]["X"] . $arParams["MATRIX"]["CELL"]["Y"] ;
?>