<?
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */

use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

$arParams["ALFAVITE"] = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q");

$arParams["MATRIX"] = array(
    "START" => array(
        "X" => "B",
        "Y" => 3,
        "ADRESS" => "B3"
    ),
    "CELL" => array(
        "X" => "B",
        "Y" => 3,
        "ADRESS" => "B3"
    )
);


$objPHPExcel = new PHPExcel();

include "style.php";
include "basket.total.php";
include "basket.head.php";

foreach ($arResult["ITEMS"]["AnDelCanBuy"] as $key => $arItem){
    include "basket.item.php";
}
$arParams["MATRIX"]["CELL"]["Y"]++;
include "basket.total.php";

# Stylesheet
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFont()->setSize(18);
$objPHPExcel->getActiveSheet()->getStyle($arParams["MATRIX"]["CELL"]["Y"])->getFont()->setBold(true);

$i = 1;
$objPHPExcel->getActiveSheet()->getStyle($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], Loc::getMessage("BASKET_TOTAL"), PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
$mergeCells = $arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"];
$mergeCells .= ":";
$mergeCells .= $arParams["ALFAVITE"][($i+4)] . $arParams["MATRIX"]["CELL"]["Y"];

$objPHPExcel->getActiveSheet()->mergeCells($mergeCells);
$objPHPExcel->getActiveSheet()->setCellValueExplicit($arParams["ALFAVITE"][$i] . $arParams["MATRIX"]["CELL"]["Y"], number_format($arResult["allSum"], 0, '.', ' ') . str_replace("#", "", $arResult["CURRENCIES_FORMAT"][$arResult["CURRENCY"]]), PHPExcel_Cell_DataType::TYPE_STRING);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

?>