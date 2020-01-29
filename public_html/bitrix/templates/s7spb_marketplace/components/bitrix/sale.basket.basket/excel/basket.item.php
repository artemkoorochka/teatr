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
$objPHPExcel->getActiveSheet()->getRowDimension($arParams["MATRIX"]["CELL"]["Y"])->setRowHeight(22);

# Data filling
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], $arItem["NAME"], PHPExcel_Cell_DataType::TYPE_STRING);
$i++;

$objPHPExcel->getActiveSheet()->getStyle($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], number_format($arItem["PRICE"], 2, '.', ' '), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;

$value = 0;
$data_value = 0;
foreach ($arParams["COLUMNS_HEADER"] as $code){
    switch ($code){
        case "PROPERTY_LHW_ctn":
            $value = $arItem["PROPERTY_Master_CTN_CBM_VALUE"];
            $value = round($value, 2);
            break;
        case "PROPERTY_DISPLAY_COUNT":
            $value = $arItem["PROPERTY_Master_CTN_PCS_VALUE"];
            $arItem["QUANTITY"] = $arItem["QUANTITY"] / $value;
            $arItem["PRICE"] = $arItem["PRICE"] * $value;
            //$arItem["SUM_VALUE"] = $arItem["SUM_VALUE"] * $value;
            break;
        case "PROPERTY_Master_CTN_PCS":
            $value = $arItem["QUANTITY"] * $arItem["PROPERTY_Master_CTN_PCS_VALUE"];
            $value = round($value, 1);
            break;
        case "PROPERTY_Master_CTN_CBM":
            $data_value = $arItem["PROPERTY_Master_CTN_CBM_VALUE"];
            $value = $arItem["QUANTITY"] * $data_value;
            $value = round($value, 2);
            break;
        case "PROPERTY_WEIGHT":
            $value = 0;
            $data_value = $arItem["PROPERTY_WEIGHT_VALUE"];
            if($data_value > 0){
                $value = $arItem["QUANTITY"] * $data_value;
                $value = round($value, 0);
            }
            break;
    }
    if($i===6){
        $objPHPExcel->getActiveSheet()->getStyle($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], $arItem["QUANTITY"], PHPExcel_Cell_DataType::TYPE_STRING);
        $i++;
    }

    $objPHPExcel->getActiveSheet()->getStyle($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], $value, PHPExcel_Cell_DataType::TYPE_STRING);
    $i++;
}

$objPHPExcel->getActiveSheet()->getStyle($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], number_format($arItem["SUM_VALUE"], 0, '.', ' '), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;


# send matrix position to new row for busket items
$arParams["MATRIX"]["CELL"]["Y"]++;
$arParams["MATRIX"]["CELL"]["X"] = $arParams["MATRIX"]["START"]["X"];
$arParams["MATRIX"]["CELL"]["ADRESS"] = $arParams["MATRIX"]["CELL"]["X"] . $arParams["MATRIX"]["CELL"]["Y"] ;
?>