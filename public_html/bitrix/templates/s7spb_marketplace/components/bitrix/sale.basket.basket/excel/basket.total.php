<?
/**
 * @var PHPExcel $objPHPExcel
 * @var array $arParams
 * @var array $arItem
 */
$i = 3;
use Bitrix\Main\Localization\Loc;

$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_TOTAL_PARAMS"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], $arResult["SPACE"]["TOTAL"]["LHW_ctn"] . Loc::getMessage("BASKET_MEASURE_SPACE"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], $arResult["SPACE"]["TOTAL"]["WEIGHT"] . Loc::getMessage("BASKET_MEASURE_WEIGHT"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], number_format($arResult["allSum"], 0, '.', ' ') . str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]), PHPExcel_Cell_DataType::TYPE_STRING);

// send matrix position to new row for busket items
$arParams["MATRIX"]["CELL"]["Y"]++;
$arParams["MATRIX"]["CELL"]["Y"]++;
$arParams["MATRIX"]["CELL"]["X"] = $arParams["MATRIX"]["START"]["X"];
$arParams["MATRIX"]["CELL"]["ADRESS"] = $arParams["MATRIX"]["CELL"]["X"] . $arParams["MATRIX"]["CELL"]["Y"] ;

AddMessage2Log($arResult["SPACE"]["TOTAL"]["LHW_ctn"]);

AddMessage2Log(Loc::getMessage("BASKET_MEASURE_SPACE", array("LHW" => $arResult["SPACE"]["TOTAL"]["LHW_ctn"])));

?>
