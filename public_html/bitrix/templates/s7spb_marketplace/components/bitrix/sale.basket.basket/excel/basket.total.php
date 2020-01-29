<?
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
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFont()->setSize(18);
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ff6f00');
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

# Data filling
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_TOTAL_PARAMS"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], $arResult["SPACE"]["TOTAL"]["LHW_ctn"] . Loc::getMessage("BASKET_MEASURE_SPACE"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], $arResult["SPACE"]["TOTAL"]["WEIGHT"] . Loc::getMessage("BASKET_MEASURE_WEIGHT"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], number_format($arResult["allSum"], 0, '.', ' ') . str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]), PHPExcel_Cell_DataType::TYPE_STRING);

# send matrix position to new row for busket items
$arParams["MATRIX"]["CELL"]["Y"]++;
$arParams["MATRIX"]["CELL"]["Y"]++;
$arParams["MATRIX"]["CELL"]["X"] = $arParams["MATRIX"]["START"]["X"];
$arParams["MATRIX"]["CELL"]["ADRESS"] = $arParams["MATRIX"]["CELL"]["X"] . $arParams["MATRIX"]["CELL"]["Y"];
?>
